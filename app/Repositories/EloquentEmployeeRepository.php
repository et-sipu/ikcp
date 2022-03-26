<?php

namespace App\Repositories;

use Exception;
use App\Models\Thumb;
use App\Models\Document;
use App\Models\Employee;
use App\Events\EmployeeUpdated;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\EmployeeQualificationInfo;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\EmployeeRepository;
use App\Repositories\Contracts\FingerprintRepository;

/**
 * Class EloquentEmployeeRepository.
 */
class EloquentEmployeeRepository extends EloquentBaseRepository implements EmployeeRepository
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var RoleRepository
     */
    private $roles;
    /**
     * @var FingerprintRepository
     */
    private $fingerprints;

    /**
     * EloquentEmployeeRepository constructor.
     * @param Employee $employee
     * @param UserRepository|null $users
     * @param RoleRepository|null $roles
     * @param FingerprintRepository|null $fingerprints
     */
    public function __construct(Employee $employee, UserRepository $users = null, RoleRepository $roles = null, FingerprintRepository $fingerprints = null)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->fingerprints = $fingerprints;
        parent::__construct($employee);
    }

    /**
     * @param $name
     *
     * @return Employee
     */
    public function find($name)
    {
        /** @var Employee $employee */
        return $this->query()->whereName($name)->first();
    }

    public function unique(Employee $employee)
    {
        if (! $employee->id) {
            if (
                (isset($employee->nric_no) && '' !== $employee->nric_no && $this->query()->where('nric_no', $employee->nric_no)->first()) ||
                $this->query()->where('name', $employee->name)->where('surname', $employee->surname)->where('date_of_birth', $employee->date_of_birth)->first()
            ) {
                return false;
            }
        } else {
            if (
                (isset($employee->nric_no) && '' !== $employee->nric_no && $this->query()->where('nric_no', $employee->nric_no)->where('id', '!=', $employee->id)->first()) ||
                $this->query()->where('name', $employee->name)->where('surname', $employee->surname)->where('date_of_birth', $employee->date_of_birth)->where('id', '!=', $employee->id)->first()
            ) {
                return false;
            }
        }

        return true;
    }

    public function unique_passport(Employee $employee, $passport_id)
    {
        if (! $passport_id) {
            return true;
        }

        if (! $employee->id) {
            if (
            Document::isPassport()->where('document_no', $passport_id)->first()
            ) {
                return false;
            }
        } else {
            if (
            Document::isPassport()->where('document_no', $passport_id)->where('documentable_id', '!=', $employee->id)->first()
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Employee
     */
    public function store(array $input)
    {
        /** @var Employee $employee */
        $input['personal_info']['name'] = mb_strtolower($input['personal_info']['name']);
        $input['personal_info']['surname'] = mb_strtolower($input['personal_info']['surname']);
        $input['personal_info']['nationality'] = $input['personal_info']['nationality']['id'];
        $employee = $this->make($input['personal_info']);

        if (! $this->unique($employee) || ! (isset($input['documents_info']['PASSPORT']) && $this->unique_passport($employee, $input['documents_info']['PASSPORT']['no']))) {
            throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.already_exist'));
        }

        DB::transaction(function () use ($employee, $input) {
            if (! $employee->save()) {
                throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.create'));
            }

            $employee->contactInfo()->create($input['contact_info']);

            $employee->financialInfo()->create($input['financial_info']);

            if (isset($input['capabilities_info'])) {
                $input['capabilities_info']['rank_id'] = $input['capabilities_info']['rank']['id'];
                unset($input['capabilities_info']['rank']);

                $employee->capabilitiesInfo()->create($input['capabilities_info']);
            }

            if (isset($input['spouse_info']) && $input['spouse_info']['name'] && 'Single' !== $employee->marital_status) {
                $employee->spouseInfo()->create($input['spouse_info']);
            }

            if (isset($input['parents_info']) && ($input['parents_info']['father_name'] || $input['parents_info']['mother_name'])) {
                $employee->parentsInfo()->create($input['parents_info']);
            }

            if (isset($input['children_info']) && 'Single' !== $employee->marital_status) {
                $employee->childrenInfo()->create(['children' => $input['children_info']]);
            }

//            if(isset($input['qualifications_info'])){
//                if(!isset($input['qualifications_info']['academic_qualifications']) || !$input['qualifications_info']['academic_qualifications']) $input['qualifications_info']['academic_qualifications'] = [];
//                if(!isset($input['qualifications_info']['professional_qualifications']) || !$input['qualifications_info']['professional_qualifications']) $input['qualifications_info']['professional_qualifications'] = [];
//                $employee->qualificationsInfo()->create($input['qualifications_info']);
//            }

            if (isset($input['qualifications_info']) && isset($input['qualifications_info']['academic_qualifications'])) {
                $this->sync_qualifications($employee, 'Academic', $input['qualifications_info']['academic_qualifications']);
            }
            if (isset($input['qualifications_info']) && isset($input['qualifications_info']['professional_qualifications'])) {
                $this->sync_qualifications($employee, 'Professional', $input['qualifications_info']['professional_qualifications']);
            }

            if (isset($input['job_info'])) {
                $input['job_info']['department_id'] = $input['job_info'] && $input['job_info']['department'] ? $input['job_info']['department']['id'] : null;
                $input['job_info']['branch_id'] = $input['job_info'] && $input['job_info']['branch'] ? $input['job_info']['branch']['id'] : null;
                $input['job_info']['report_to_id'] = $input['job_info'] && $input['job_info']['report_to'] ? $input['job_info']['report_to']['id'] : null;
                $input['job_info']['employment_level_id'] = $input['job_info'] && $input['job_info']['level'] ? $input['job_info']['level']['id'] : null;
                $input['job_info']['designation_id'] = $input['job_info'] && $input['job_info']['designation'] ? $input['job_info']['designation']['id'] : null;
                $employee->jobInfo()->create(array_except($input['job_info'], ['email', 'thumbs']));
                if(strlen(trim($input['job_info']['thumbs']))){
                    $thumbs = explode(',', $input['job_info']['thumbs']);
                    if (\count($thumbs)) {
                        $this->sync_thumbs($employee, $thumbs);
                    }
                }
            }

            if (isset($input['job_info']) && isset($input['job_info']['email'])) {
                $user = $this->getLinkedUser($input);
                $employee->user()->associate($user);
                $employee->save();
            }

            $this->sync_documents($employee, $input['documents_info']);

            if (isset($input['photo'])) {
                $employee->addMedia($input['photo'])->toMediaCollection('photo');
            }

            if (isset($input['signature'])) {
                $employee->addMedia($input['signature'])->toMediaCollection('signature');
            }

            return true;
        });

        return $employee;
    }

    /**
     * @param $employee
     * @param $thumbs
     *
     * @throws GeneralException
     * @throws \ReflectionException
     *
     * @return bool
     */
    public function sync_thumbs($employee, $thumbs)
    {
        $employee->thumbs()->whereIn('staff_id', array_diff($employee->thumbs->pluck('staff_id')->toArray(), $thumbs))->delete();
        $thumbs_need_attendance = array_diff($thumbs, $employee->thumbs->pluck('staff_id')->toArray());
        if (\count($thumbs)) {
            foreach ($thumbs as $thumb) {
                if (Thumb::where('staff_id', $thumb)->where('employee_id', '!=', $employee->id)->first()) {
                    throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.duplicate_staff_id'));
                }
                $employee->thumbs()->firstOrCreate(['staff_id' => $thumb]);
            }
            $this->fingerprints->create_attendance_records($employee, $thumbs_need_attendance);
        }

        return true;
    }

    /**
     * @param $input
     *
     * @throws GeneralException
     * @throws \ReflectionException
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function getLinkedUser($input)
    {
        $user = $this->users->query()->where('email', $input['job_info']['email'])->first();

        if ($user && $this->query()->where('user_id', $user->id)->first()) {
            throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.email_used'));
        } elseif (! $user) {
            $user = $this->users->store([
                'email'  => $input['job_info']['email'],
                'name'   => $input['personal_info']['name'].' '.$input['personal_info']['surname'],
                'active' => true,
                'force'  => true,
                'roles'  => [
                    $this->roles->query()->where('name', 'employee')->first()->id,
                ],
            ], true);
        }

        return $user;
    }

    /**
     * @param Employee $employee
     * @param array    $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Employee
     */
    public function update(Employee $employee, array $input)
    {
        $input['personal_info']['name'] = mb_strtolower($input['personal_info']['name']);
        $input['personal_info']['surname'] = mb_strtolower($input['personal_info']['surname']);
        $input['personal_info']['nationality'] = $input['personal_info']['nationality']['id'];

        $employee->fill($input['personal_info']);

        if (! $this->unique($employee) || ! (isset($input['documents_info']['PASSPORT']) && $this->unique_passport($employee, $input['documents_info']['PASSPORT']['no']))) {
            throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.already_exist'));
        }

        if (! $employee->save()) {
            throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.update'));
        }

        DB::transaction(function () use ($employee, $input) {
            if (! $employee->save()) {
                throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.create'));
            }

            if ($employee->contactInfo) {
                $employee->contactInfo->update($input['contact_info']);
            } else {
                $employee->contactInfo()->create($input['contact_info']);
            }

            if ($employee->financialInfo) {
                $employee->financialInfo->update($input['financial_info']);
            } else {
                $employee->financialInfo()->create($input['financial_info']);
            }

            if ($employee->capabilitiesInfo) {
                $input['capabilities_info']['rank_id'] = $input['capabilities_info']['rank']['id'];
                $employee->capabilitiesInfo->update($input['capabilities_info']);
            } elseif (isset($input['capabilities_info'])) {
                $input['capabilities_info']['rank_id'] = $input['capabilities_info']['rank']['id'];
                $employee->capabilitiesInfo()->create($input['capabilities_info']);
            }

            if ('Single' === $employee->marital_status) {
                if ($employee->spouseInfo) {
                    $employee->spouseInfo->delete();
                }
            } elseif ($employee->spouseInfo && isset($input['spouse_info']) && $input['spouse_info']['name']) {
                $employee->spouseInfo->update($input['spouse_info']);
            } elseif (isset($input['spouse_info']) && $input['spouse_info']['name']) {
                $employee->spouseInfo()->create($input['spouse_info']);
            }

            if ($employee->parentsInfo && isset($input['parents_info']) && ($input['parents_info']['father_name'] || $input['parents_info']['mother_name'])) {
                $employee->parentsInfo->update($input['parents_info']);
            } elseif (isset($input['parents_info']) && ($input['parents_info']['father_name'] || $input['parents_info']['mother_name'])) {
                $employee->parentsInfo()->create($input['parents_info']);
            }

            if ('Single' === $employee->marital_status) {
                if ($employee->childrenInfo) {
                    $employee->childrenInfo->delete();
                }
            } elseif ($employee->childrenInfo) {
                $employee->childrenInfo->update(['children' => $input['children_info']]);
            } elseif (isset($input['spouse_info'])) {
                $employee->childrenInfo()->create(['children' => $input['children_info']]);
            }

            if (isset($input['qualifications_info']) && isset($input['qualifications_info']['academic_qualifications'])) {
                $this->sync_qualifications($employee, 'Academic', $input['qualifications_info']['academic_qualifications']);
            }
            if (isset($input['qualifications_info']) && isset($input['qualifications_info']['professional_qualifications'])) {
                $this->sync_qualifications($employee, 'Professional', $input['qualifications_info']['professional_qualifications']);
            }

            if (isset($input['job_info'])) {
                $input['job_info']['department_id'] = $input['job_info'] && $input['job_info']['department'] ? $input['job_info']['department']['id'] : null;
                $input['job_info']['branch_id'] = $input['job_info'] && $input['job_info']['branch'] ? $input['job_info']['branch']['id'] : null;
                $input['job_info']['report_to_id'] = $input['job_info'] && $input['job_info']['report_to'] ? $input['job_info']['report_to']['id'] : null;
                $input['job_info']['employment_level_id'] = $input['job_info'] && $input['job_info']['level'] ? $input['job_info']['level']['id'] : null;
                $input['job_info']['designation_id'] = $input['job_info'] && $input['job_info']['designation'] ? $input['job_info']['designation']['id'] : null;

                if ($employee->jobInfo) {
                    $employee->jobInfo->update(array_except($input['job_info'], ['email', 'thumbs']));
                } elseif (isset($input['job_info'])) {
                    $employee->jobInfo()->create(array_except($input['job_info'], 'email', 'thumbs'));
                }

                $thumbs = explode(',', $input['job_info']['thumbs']);
                if(strlen(trim($input['job_info']['thumbs']))) {
                    $this->sync_thumbs($employee, $thumbs);
                }
            }

            if (isset($input['job_info']) && isset($input['job_info']['email']) && ! $employee->user) {
                $user = $this->getLinkedUser($input);

                $employee->user()->associate($user);
                $employee->save();
            }

            $this->sync_documents($employee, $input['documents_info'] ? $input['documents_info'] : []);

            if (isset($input['photo'])) {
                if ($employee->getMedia('photo')->first()) {
                    $employee->deleteMedia($employee->getMedia('photo')->first());
                }

                $employee->addMedia($input['photo'])->toMediaCollection('photo');
            }

            if (isset($input['signature'])) {
                if ($employee->getMedia('signature')->first()) {
                    $employee->deleteMedia($employee->getMedia('signature')->first());
                }

                $employee->addMedia($input['signature'])->toMediaCollection('signature');
            }

            return true;
        });

        event(new EmployeeUpdated($employee));

        return $employee;
    }

    /**
     * @param Employee $employee
     * @param array    $documents_array
     *
     * @throws \Throwable
     *
     * @return bool
     */
    protected function sync_documents(Employee $employee, $documents_array = [])
    {
        DB::transaction(function () use ($employee, $documents_array) {
            foreach ($documents_array as $doc_type => $document) {
                if (isset($document['id']) && 0 !== $document['id']) {
                    $employeeDoc = Document::find($document['id']);
                    if (! $employeeDoc) {
                        continue;
                    }
                    if ((! isset($document['no']) || '' === $document['no']) && (! isset($document['expiry']) || '' === $document['expiry'])) {
                        $employeeDoc->delete();
                        continue;
                    }
                    $employeeDoc->document_no = $document['no'];
                    $employeeDoc->document_expiry_date = $document['expiry'];
                    $employeeDoc->document_type = mb_strtoupper($doc_type);
                    $employeeDoc->save();
                } else {
                    if ((! isset($document['no']) || empty($document['no'])) && (! isset($document['expiry']) || empty($document['expiry']))) {
                        continue;
                    }
                    $employeeDoc = new Document();
                    $employeeDoc->document_no = $document['no'];
                    $employeeDoc->document_expiry_date = $document['expiry'];
                    $employeeDoc->document_type = mb_strtoupper($doc_type);
                    $employee->documents()->save($employeeDoc);
                }

                if ((isset($document['file']) && $document['file']) || (isset($document['id']) && 0 !== $document['id'] && isset($document['url']) && 'DROP' === $document['url'])) {
                    if ($employee->getMedia($employeeDoc->document_type)->first()) {
                        $employee->deleteMedia($employee->getMedia($employeeDoc->document_type)->first());
                    }

                    if (isset($document['file']) && $document['file']) {
                        $employee->addMedia($document['file'])->toMediaCollection($employeeDoc->document_type);
                    }
                }
            }

            return true;
        });

        return true;
    }

    /**
     * @param Employee $employee
     * @param string   $type
     * @param array    $qualifications
     *
     * @throws \Throwable
     *
     * @return bool
     */
    protected function sync_qualifications(Employee $employee, $type = '', $qualifications = [])
    {
        DB::transaction(function () use ($employee, $qualifications, $type) {
            if (! $qualifications) {
                $employee->qualificationsInfo()->where('type', $type)->each(function ($document) {
                    $document->delete();
                });

                return;
            }
            $ids_list = array_filter(array_column($qualifications, 'id'));

            $employee->qualificationsInfo()->where('type', $type)->whereNotIn('id', $ids_list)->get()->each(function ($qualification) {
                $qualification->delete();
            });

            foreach ($qualifications as $qualification) {
                $qualification_type = $type;

                if (isset($qualification['id']) && 0 !== $qualification['id']) {
                    $employeeQualification = EmployeeQualificationInfo::find($qualification['id']);
                    if (! $employeeQualification) {
                        continue;
                    }
                    $employeeQualification->authority = $qualification['authority'];
                    $employeeQualification->degree = $qualification['degree'];
                    $employeeQualification->year = $qualification['year'];
                    $employeeQualification->type = $qualification_type;
                    $employeeQualification->save();

                    if (isset($qualification['file']) && $qualification['file']) {
                        if ($media = $employeeQualification->getMedia('photocopy')->first()) {
                            $employeeQualification->deleteMedia($media);
                        }

                        $employeeQualification->addMedia($qualification['file'])->toMediaCollection('photocopy');
                    }
                } else {
                    $employeeQualification = new EmployeeQualificationInfo();
                    $employeeQualification->authority = $qualification['authority'];
                    $employeeQualification->degree = $qualification['degree'];
                    $employeeQualification->year = $qualification['year'];
                    $employeeQualification->type = $qualification_type;
                    $employee->qualificationsInfo()->save($employeeQualification);

                    if (isset($qualification['file']) && $qualification['file']) {
                        $employeeQualification->addMedia($qualification['file'])->toMediaCollection('photocopy');
                    }
                }
            }

            return true;
        });

        return true;
    }

    /**
     * @param Employee $employee
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Employee $employee)
    {
        if (! $employee->delete()) {
            throw new GeneralException(__('exceptions.backend.'.$this->get_plurar_model_name().'.delete'));
        }

        return true;
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function get_plurar_model_name(): string
    {
        return str_plural(mb_strtolower((new \ReflectionClass($this->model))->getShortName()));
    }

    /**
     * @return array|mixed
     */
    public function get_employees_emails()
    {
        $emails = [];

        $employees = $this->query()->with('user')->whereHas('jobInfo', function ($query){$query->whereIn('employment_status', ['on_probation' , 'permanent']);})
            ->get()->pluck('user','full_name')->filter(function ($user){return $user != null && $user->active == 1;})->toArray();

        foreach ($employees as $name => $employee){
            $emails[$employee['email']] = $name."(".$employee['email'].")";
        }

        return $emails;
    }

}

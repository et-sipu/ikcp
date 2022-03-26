<?php

namespace App\Http\Controllers\Backend;

use App\Models\Employee;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Transformers\AuditTransformer;
use App\Transformers\EmployeeTransformer;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Repositories\Contracts\EmployeeRepository;

class EmployeeController extends BackendController
{
    /**
     * @var EmployeeRepository
     */
    protected $employees;

    protected $repo_name = 'employees';

    /**
     * Create a new controller instance.
     *
     * @param EmployeeRepository $employees
     */
    public function __construct(EmployeeRepository $employees)
    {
        $this->employees = $employees;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array
     */
    public function search(Request $request)
    {
        //abort(403);
        $query = $this->employees->query()->with(['jobInfo', 'documents']);
        $query->leftJoin('employee_job_infos', 'employee_job_infos.employee_id', '=', 'employees.id');
        $query->leftJoin('departments', 'departments.id', '=', 'employee_job_infos.department_id');
        $query->leftJoin('branches', 'branches.id', '=', 'employee_job_infos.branch_id');

        $query->select([DB::raw('concat(employees.name,\' \',surname) as full_name'), DB::raw('departments.name as department_name'), DB::raw('branches.name as branch_name'), 'employees.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->nationalities) && \is_array($extraSearch->nationalities) && \count($extraSearch->nationalities) > 0) {
            $nationalities = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->nationalities);
            $query = $query->hasNationalityOf($nationalities);
        }
        if (isset($extraSearch->ranks) && \is_array($extraSearch->ranks) && \count($extraSearch->ranks) > 0) {
            $query = $query->hasRank(array_column($extraSearch->ranks, 'id'));
        }
        if (isset($extraSearch->coc_certificate_types) && \is_array($extraSearch->coc_certificate_types) && \count($extraSearch->coc_certificate_types) > 0) {
            $query = $query->hasCOC($extraSearch->coc_certificate_types);
        }
        if (isset($extraSearch->security_courses) && \is_array($extraSearch->security_courses) && \count($extraSearch->security_courses) > 0) {
            $query = $query->hasSecurityCources($extraSearch->security_courses);
        }
        if (isset($extraSearch->other_courses) && \is_array($extraSearch->other_courses) && \count($extraSearch->other_courses) > 0) {
            $query = $query->hasCources($extraSearch->other_courses);
        }
        if (isset($extraSearch->available_for_contracting)) {
            $query = $query->availableForContracting($extraSearch->available_for_contracting);
        }
        if (isset($extraSearch->passport_no) && ! empty($extraSearch->passport_no)) {
            $query = $query->withPassport($extraSearch->passport_no);
        }
        if (isset($extraSearch->smc_reg_no) && ! empty($extraSearch->smc_reg_no)) {
            $query = $query->withSMC($extraSearch->smc_reg_no);
        }

        if (isset($extraSearch->departments) && \is_array($extraSearch->departments) && \count($extraSearch->departments) > 0) {
            $department_ids = array_pluck($extraSearch->departments, 'id');
            $query = $query->InOneOfDepartments($department_ids);
        }

        if (isset($extraSearch->branches) && \is_array($extraSearch->branches) && \count($extraSearch->branches) > 0) {
            $branch_ids = array_pluck($extraSearch->branches, 'id');
            $query = $query->InOneOfBranches($branch_ids);
        }

        if (! Gate::check('view employees')) {
            $query->where('employees.id', auth()->user()->employee ? auth()->user()->employee->id : 0);
//            if(auth()->user()->employee){
//                $query->whereId(auth()->user()->employee->id);
//            }
//            else {
//                $query->where(function(Builder $q){
//                    return $q->orWhereNull('user_id')->orWhere('user_id',auth()->user()->id);
//                });
//            }
        }

//        $requestSearchQuery = new RequestSearchQuery($request, $query, ['`employees`.`name-surname`', 'nric_no', 'date_of_birth', 'place_of_birth']);
        $requestSearchQuery = new RequestSearchQuery($request, $query, ['`employees.name`-`employees.surname`', 'nric_no', 'date_of_birth', 'place_of_birth']);

        return $requestSearchQuery->result(new EmployeeTransformer());
    }

    /**
     * @param Employee $employee
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Employee $employee)
    {
        $this->authorize('view own employees');

        return Fractal::create()->item($employee, new EmployeeTransformer(true));
    }

    /**
     * @param StoreEmployeeRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create employees');

        $this->employees->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.employees.created'));
    }

    /**
     * @param Employee              $employee
     * @param UpdateEmployeeRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Employee $employee, UpdateEmployeeRequest $request)
    {
        $this->authorize('edit', $employee);

        $this->employees->update($employee, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.employees.updated'));
    }

    /**
     * @param Employee $employee
     * @param Request  $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Employee $employee, Request $request)
    {
        $this->authorize('delete employees');

        $this->employees->destroy($employee);

        return $this->redirectResponse($request, __('alerts.backend.employees.deleted'));
    }

    public function getHistory(Request $request, $auditable_id)
    {
        $employee = $this->{$this->repo_name}->query()->find($auditable_id);
        $relations = [];
        $relations['EmployeeContactInfo'] = $employee->contactInfo ? $employee->contactInfo->id : null;
        $relations['EmployeeFinancialInfo'] = $employee->financialInfo ? $employee->financialInfo->id : null;
        $relations['EmployeeSpouseInfo'] = $employee->spouseInfo ? $employee->spouseInfo->id : null;
        $relations['EmployeeParentsInfo'] = $employee->parentsInfo ? $employee->parentsInfo->id : null;
        $relations['EmployeeJobInfo'] = $employee->jobInfo ? $employee->jobInfo->id : null;
        $relations['EmployeeChildrenInfo'] = $employee->childrenInfo ? $employee->childrenInfo->id : null;
        $relations['EmployeeQualificationInfo'] = $employee->qualificationsInfo ? $employee->qualificationsInfo->pluck('id')->toArray() : null;
        $relations['Document'] = $employee->documents ? $employee->documents->pluck('id')->toArray() : null;

        $query = Audit::query();
        foreach ($relations as $type => $id) {
            if (! $id) {
                continue;
            }
            if (\is_array($id)) {
                foreach ($id as $item_id) {
                    $query->orWhere(function ($q) use ($type, $item_id) {
                        return $q->where('auditable_id', $item_id)->where('auditable_type', $type);
                    });
                }
            } else {
                $query->orWhere(function ($q) use ($id, $type) {
                    return $q->where('auditable_id', $id)->where('auditable_type', $type);
                });
            }
        }
        $query->orWhere(function ($q) use ($auditable_id) {
            return $q->where('auditable_id', $auditable_id)->where('auditable_type', 'Employee');
        });

        $query->orderBy('id', 'desc');

        $query->with('user');
        if ($request->has('current_page') && $request->has('per_page')) {
            $query = $query->limit($request->get('per_page'))->offset($request->get('per_page') * $request->get('current_page'));
        }

        return Fractal::create()->collection($query->get())
            ->transformWith(new AuditTransformer())
            ->toArray();
    }

    /**
     * @param Employee|null $employee
     * @return array
     */
    public function getContractingInfo(Employee $employee = null)
    {
        $employee = $employee ?: auth()->user()->employee;

        return ['start_date' => $employee->latest_contracting_date->format('Y-m-d'), 'end_date' => $employee->end_of_contract_date->format('Y-m-d')];
    }

    public function getEmployeesEmails()
    {
        return $this->employees->get_employees_emails();
    }
}

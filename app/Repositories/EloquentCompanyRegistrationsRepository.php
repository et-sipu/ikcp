<?php

namespace App\Repositories;

use Exception;
use App\Models\CompanyRegistrations;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\CompanyRegistrationsRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentCompanyRegistrationsRepository.
 */
class EloquentCompanyRegistrationsRepository extends EloquentBaseRepository implements CompanyRegistrationsRepository
{
    /**
     * EloquentCompanyRegistrationsRepository constructor.
     *
     * @param CompanyRegistrations $company_registration
     */
    public function __construct(CompanyRegistrations $company_registration)
    {
        parent::__construct($company_registration);
    }

//    /**
//     * @param $name
//     *
//     * @return CompanyRegistrations
//     */
//    public function find($name)
//    {
//        /* @var CompanyRegistrations $company_registration */
//        return false;//$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\CompanyRegistrations
     */
    public function store(array $input)
    {
        /** @var CompanyRegistrations $company_registration */
        $company_registration = $this->make(array_except($input, []));

//        if ($this->find($company_registration->name)) {
//            throw new GeneralException(__('exceptions.backend.company_registrations.already_exist'));
//        }

        DB::transaction(function () use ( $company_registration, $input) {
            if (!$company_registration->save()) {
                throw new GeneralException(__('exceptions.backend.company_registrations.create'));
            }

            if (isset($input['registration_file'])) {
                $company_registration->addMedia($input['registration_file'])->toMediaCollection('registration_file');
            }

            return true;
        });

        return $company_registration;
    }

    /**
     * @param CompanyRegistrations $company_registration
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\CompanyRegistrations
     */
    public function update(CompanyRegistrations $company_registration, array $input)
    {
//        if (($existingCompanyRegistrations = $this->find($company_registration->name))
//          && $existingCompanyRegistrations->id !== $company_registration->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.company_registrations.already_exist'));
//        }
        
        DB::transaction(function () use ( $company_registration, $input) {
            if (!$company_registration->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.company_registrations.update'));
            }

            if (isset($input['registration_file'])) {
                if ($company_registration->getMedia('registration_file')->first()) {
                    $company_registration->deleteMedia($company_registration->getMedia('registration_file')->first());
                }
                $company_registration->addMedia($input['registration_file'])->toMediaCollection('registration_file');
            }

            return true;
        });

        return $company_registration;
    }

    /**
     * @param CompanyRegistrations $company_registration
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(CompanyRegistrations $company_registration)
    {
        if (! $company_registration->delete()) {
            throw new GeneralException(__('exceptions.backend.company_registrations.delete'));
        }

        return true;
    }
}

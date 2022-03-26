<?php

namespace App\Repositories\Contracts;

use App\Models\CompanyRegistrations;

/**
 * Interface CompanyRegistrationslRepository.
 */
interface CompanyRegistrationsRepository extends BaseRepository
{
//

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param CompanyRegistrations $company_registration
     * @param array       $input
     *
     * @return mixed
     */
    public function update(CompanyRegistrations $company_registration, array $input);

    /**
     * @param CompanyRegistrations $company_registration
     *
     * @return mixed
     */
    public function destroy(CompanyRegistrations $company_registration);
}

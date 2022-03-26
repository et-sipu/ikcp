<?php

namespace App\Repositories\Contracts;

use App\Models\Seafarer;

/**
 * Interface SeafarerRepository.
 */
interface SeafarerRepository extends EmployeeRepository
{
    /**
     * @param Seafarer $seafarer
     * @param $comment
     *
     * @return mixed
     */
    public function blacklist(Seafarer $seafarer, $comment);

    /**
     * @param Seafarer $seafarer
     * @param $comment
     *
     * @return mixed
     */
    public function whitelist(Seafarer $seafarer, $comment);
}

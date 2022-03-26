<?php

namespace App\Repositories\Contracts;

use App\Models\Holiday;

/**
 * Interface HolidaylRepository.
 */
interface HolidayRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Holiday
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Holiday $holiday
     * @param array   $input
     *
     * @return mixed
     */
    public function update(Holiday $holiday, array $input);

    /**
     * @param Holiday $holiday
     *
     * @return mixed
     */
    public function destroy(Holiday $holiday);
}

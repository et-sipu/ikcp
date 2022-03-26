<?php

namespace App\Repositories\Contracts;

use App\Models\Activity;

/**
 * Interface ActivitylRepository.
 */
interface ActivityRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Activity
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Activity $activity
     * @param array    $input
     *
     * @return mixed
     */
    public function update(Activity $activity, array $input);

    /**
     * @param Activity $activity
     *
     * @return mixed
     */
    public function destroy(Activity $activity);

    /**
     * @param Activity $activity
     * @param $approved_cost
     *
     * @return mixed
     */
    public function approve(Activity $activity, $approved_cost);

    /**
     * @param Activity $activity
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Activity $activity, $new_status);
}

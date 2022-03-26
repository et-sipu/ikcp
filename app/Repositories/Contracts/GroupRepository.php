<?php

namespace App\Repositories\Contracts;

use App\Models\Group;

/**
 * Interface GrouplRepository.
 */
interface GroupRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Group
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Group $group
     * @param array       $input
     *
     * @return mixed
     */
    public function update(Group $group, array $input);

    /**
     * @param Group $group
     *
     * @return mixed
     */
    public function destroy(Group $group);
}

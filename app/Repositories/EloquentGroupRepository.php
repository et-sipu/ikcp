<?php

namespace App\Repositories;

use Exception;
use App\Models\Group;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\GroupRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentGroupRepository.
 */
class EloquentGroupRepository extends EloquentBaseRepository implements GroupRepository
{
    /**
     * EloquentGroupRepository constructor.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        parent::__construct($group);
    }

    /**
     * @param $name
     *
     * @return Group
     */
    public function find($name)
    {
        /* @var Group $group */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Group
     */
    public function store(array $input)
    {
        /** @var Group $group */
        $group = $this->make(array_except($input, ['members']));
        $members = array_pluck($input['members'], 'id');


        if ($this->find($group->name)) {
            throw new GeneralException(__('exceptions.backend.groups.already_exist'));
        }

        DB::transaction(function () use ( $group, $input, $members) {
            if (!$group->save()) {
                throw new GeneralException(__('exceptions.backend.groups.create'));
            }

            $group->users()->sync($members);

            return true;
        });

        return $group;
    }

    /**
     * @param Group $group
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Group
     */
    public function update(Group $group, array $input)
    {
        $members = array_pluck($input['members'], 'id');
        if (($existingGroup = $this->find($group->name))
            && $existingGroup->id !== $group->id
        ) {
            throw new GeneralException(__('exceptions.backend.groups.already_exist'));
        }

        DB::transaction(function () use ( $group, $input, $members) {
            if (!$group->update(array_except($input, ['members']))) {
                throw new GeneralException(__('exceptions.backend.groups.update'));
            }

            $group->users()->sync($members);

            return true;
        });

        return $group;
    }

    /**
     * @param Group $group
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Group $group)
    {
        if (! $group->delete()) {
            throw new GeneralException(__('exceptions.backend.groups.delete'));
        }

        return true;
    }
}

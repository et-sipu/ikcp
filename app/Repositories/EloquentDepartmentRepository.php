<?php

namespace App\Repositories;

use Exception;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\DepartmentRepository;

/**
 * Class EloquentDepartmentRepository.
 */
class EloquentDepartmentRepository extends EloquentBaseRepository implements DepartmentRepository
{
    /**
     * EloquentDepartmentRepository constructor.
     *
     * @param Department $department
     */
    public function __construct(Department $department)
    {
        parent::__construct($department);
    }

    /**
     * @param $name
     *
     * @return Department
     */
    public function find($name)
    {
        /** @var Department $department */
        return $this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Department
     */
    public function store(array $input)
    {
        /** @var Department $department */
        $input['hod_id'] = $input['hod']['id'];
        $department = $this->make(array_except($input, []));

        if ($this->find($department->name)) {
            throw new GeneralException(__('exceptions.backend.departments.already_exist'));
        }

        DB::transaction(function () use ($department) {
            if (! $department->save()) {
                throw new GeneralException(__('exceptions.backend.departments.create'));
            }

            return true;
        });

        return $department;
    }

    /**
     * @param Department $department
     * @param array      $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Department
     */
    public function update(Department $department, array $input)
    {
        $input['hod_id'] = $input['hod']['id'];

        if (($existingDepartment = $this->find($department->name))
          && $existingDepartment->id !== $department->id
        ) {
            throw new GeneralException(__('exceptions.backend.departments.already_exist'));
        }

        DB::transaction(function () use ($department, $input) {
            if (! $department->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.departments.update'));
            }

            return true;
        });

        return $department;
    }

    /**
     * @param Department $department
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Department $department)
    {
        if (! $department->delete()) {
            throw new GeneralException(__('exceptions.backend.departments.delete'));
        }

        return true;
    }
}

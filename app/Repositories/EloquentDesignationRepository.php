<?php

namespace App\Repositories;

use Exception;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\DesignationRepository;

/**
 * Class EloquentDesignationRepository.
 */
class EloquentDesignationRepository extends EloquentBaseRepository implements DesignationRepository
{
    /**
     * EloquentDesignationRepository constructor.
     *
     * @param Designation $designation
     */
    public function __construct(Designation $designation)
    {
        parent::__construct($designation);
    }

    /**
     * @param $name
     *
     * @return Designation
     */
    public function find($name)
    {
        /** @var Designation $designation */
        return $this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Designation
     */
    public function store(array $input)
    {
        $input['department_id'] = $input['department']['id'];
        /** @var Designation $designation */
        $designation = $this->make(array_except($input, []));

        if ($this->find($designation->name)) {
            throw new GeneralException(__('exceptions.backend.designations.already_exist'));
        }

        DB::transaction(function () use ($designation) {
            if (! $designation->save()) {
                throw new GeneralException(__('exceptions.backend.designations.create'));
            }

            return true;
        });

        return $designation;
    }

    /**
     * @param Designation $designation
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Designation
     */
    public function update(Designation $designation, array $input)
    {
        $input['department_id'] = $input['department']['id'];

        if (($existingDesignation = $this->find($designation->name))
          && $existingDesignation->id !== $designation->id
        ) {
            throw new GeneralException(__('exceptions.backend.designations.already_exist'));
        }

        DB::transaction(function () use ($designation, $input) {
            if (! $designation->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.designations.update'));
            }

            return true;
        });

        return $designation;
    }

    /**
     * @param Designation $designation
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Designation $designation)
    {
        if (! $designation->delete()) {
            throw new GeneralException(__('exceptions.backend.designations.delete'));
        }

        return true;
    }
}

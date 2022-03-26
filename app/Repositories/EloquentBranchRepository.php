<?php

namespace App\Repositories;

use Exception;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\BranchRepository;

/**
 * Class EloquentBranchRepository.
 */
class EloquentBranchRepository extends EloquentBaseRepository implements BranchRepository
{
    /**
     * EloquentBranchRepository constructor.
     *
     * @param Branch $branch
     */
    public function __construct(Branch $branch)
    {
        parent::__construct($branch);
    }

//    /**
//     * @param $name
//     *
//     * @return Branch
//     */
//    public function find($name)
//    {
//        /** @var Branch $branch */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Branch
     */
    public function store(array $input)
    {
        /** @var Branch $branch */
        $branch = $this->make(array_except($input, []));

//        if ($this->find($branch->name)) {
//            throw new GeneralException(__('exceptions.backend.branchs.already_exist'));
//        }

        DB::transaction(function () use ($branch) {
            if (! $branch->save()) {
                throw new GeneralException(__('exceptions.backend.branchs.create'));
            }

            return true;
        });

        return $branch;
    }

    /**
     * @param Branch $branch
     * @param array  $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Branch
     */
    public function update(Branch $branch, array $input)
    {
//        if (($existingBranch = $this->find($branch->name))
//          && $existingBranch->id !== $branch->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.branchs.already_exist'));
//        }

        DB::transaction(function () use ($branch, $input) {
            if (! $branch->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.branchs.update'));
            }

            return true;
        });

        return $branch;
    }

    /**
     * @param Branch $branch
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Branch $branch)
    {
        if (! $branch->delete()) {
            throw new GeneralException(__('exceptions.backend.branchs.delete'));
        }

        return true;
    }
}

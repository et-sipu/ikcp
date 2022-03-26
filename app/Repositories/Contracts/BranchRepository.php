<?php

namespace App\Repositories\Contracts;

use App\Models\Branch;

/**
 * Interface BranchlRepository.
 */
interface BranchRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Branch
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Branch $branch
     * @param array  $input
     *
     * @return mixed
     */
    public function update(Branch $branch, array $input);

    /**
     * @param Branch $branch
     *
     * @return mixed
     */
    public function destroy(Branch $branch);
}

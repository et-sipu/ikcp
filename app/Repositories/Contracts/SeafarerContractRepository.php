<?php

namespace App\Repositories\Contracts;

use App\Models\Signon;
use App\Models\SeafarerContract;

/**
 * Interface SeafarerRepository.
 */
interface SeafarerContractRepository extends BaseRepository
{
    /**
     * @param $seafarer_id
     *
     * @return SeafarerContract
     */
    public function find($seafarer_id);

    /**
     * @param array $input
     * @param bool  $fake
     *
     * @return mixed
     */
    public function store(array $input, $fake = false);

    /**
     * @param SeafarerContract $contract
     * @param array            $input
     *
     * @return mixed
     */
    public function update(SeafarerContract $contract, array $input);

    /**
     * @param SeafarerContract $contract
     *
     * @return mixed
     */
    public function destroy(SeafarerContract $contract);

    /**
     * @param SeafarerContract $contract
     * @param $status
     *
     * @return mixed
     */
    public function changeStatus(SeafarerContract $contract, $status);

    /**
     * @param SeafarerContract $contract
     * @param array            $input
     *
     * @return mixed
     */
    public function addSign(SeafarerContract $contract, array $input);

    /**
     * @param SeafarerContract $contract
     * @param Signon           $sign
     * @param null             $sign_date
     *
     * @return mixed
     */
    public function approveSign(SeafarerContract $contract, Signon $sign, $sign_date = null);

    /**
     * @param SeafarerContract $contract
     * @param Signon           $sign
     *
     * @return mixed
     */
    public function deleteSign(SeafarerContract $contract, Signon $sign);
}

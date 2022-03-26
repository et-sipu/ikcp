<?php

namespace App\Repositories;

use Exception;
use App\Models\Signon;
use App\Models\CrewRequest;
use App\Models\SeafarerContract;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\EmployeeCapabilitiesInfo;
use App\Events\SeafarerContractStatusChanged;
use App\Repositories\Contracts\SeafarerContractRepository;

/**
 * Class EloquentSeafarerContractRepository.
 */
class EloquentSeafarerContractRepository extends EloquentBaseRepository implements SeafarerContractRepository
{
    /**
     * EloquentSeafarerContractRepository constructor.
     *
     * @param SeafarerContract $contract
     */
    public function __construct(SeafarerContract $contract)
    {
        parent::__construct($contract);
    }

    /**
     * @param $seafarer_id
     *
     * @return SeafarerContract
     */
    public function find($seafarer_id)
    {
        /** @var SeafarerContract $contract */
        return $this->query()->where('seafarer_id', $seafarer_id)->whereStatus(SeafarerContract::$pending)->first();
    }

    /**
     * @param array $input
     * @param bool  $fake
     *
     * @throws GeneralException
     *
     * @return \App\Models\SeafarerContract
     */
    public function store(array $input, $fake = false)
    {
        /** @var SeafarerContract $contract */
        $contract = $this->make(array_except($input, ['sign_on_port', 'seafarer', 'vessel']));
        $contract->sign_on_port_id = $input['sign_on_port']['id'];
        $contract->seafarer_id = $input['seafarer']['id'];
        $contract->vessel_id = $input['vessel']['id'];
        $contract->contract_rank_id = $input['rank']['id'];
        $contract->remarks = $input['remarks'];

        if ($fake) {
            return $contract;
        }

        if (! $contract->seafarer->available_for_contracting($input['scheduled_sign_on_date'])) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.already_exist'));
        }

        if ($input['crew_request_id'] && $crew_request = CrewRequest::find($input['crew_request_id'])) {
            $crew_request->status = 'done';
            $crew_request->save();
        }

        if (! $contract->save()) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.create'));
        }

        return $contract;
    }

    /**
     * @param SeafarerContract $contract
     * @param array            $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\SeafarerContract
     */
    public function update(SeafarerContract $contract, array $input)
    {
        $data = array_except($input, ['sign_on_port', 'seafarer', 'vessel']);
        $data['sign_on_port_id'] = $input['sign_on_port']['id'];
        $data['vessel_id'] = $input['vessel']['id'];
        $data['contract_rank_id'] = $input['rank']['id'];
        $data['remarks'] = $input['remarks'];

        DB::transaction(function () use ($contract, $input, $data) {
            if (! $contract->update($data)) {
                throw new GeneralException(__('exceptions.backend.seafarer_contracts.update'));
            }

            if (isset($input['signed_contract']) && $input['signed_contract'] && $input['signed_contract']->isValid()) {
                if ($contract->getMedia('signed_contract')->first()) {
                    $contract->deleteMedia($contract->getMedia('signed_contract')->first());
                }

                $contract->addMedia($input['signed_contract'])->toMediaCollection('signed_contract');
            }

            return true;
        });

        return $contract;
    }

    /**
     * @param SeafarerContract $contract
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(SeafarerContract $contract)
    {
        if (! $contract->delete()) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.delete'));
        }

        return true;
    }

    /**
     * @param SeafarerContract $contract
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool|mixed
     */
    public function changeStatus(SeafarerContract $contract, $status)
    {
        if ($status === SeafarerContract::$approved && $contract->seafarer->ongoing_contract) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.already_exist'));
        }

        $contract->status = $status;
        if (! $contract->save()) {
            throw new GeneralException(__("exceptions.backend.seafarer_contracts.$status"));
        }

        event(new SeafarerContractStatusChanged($contract, $status));

        return true;
    }

    /**
     * @param SeafarerContract $contract
     * @param array            $input
     *
     * @throws GeneralException
     * @throws \Throwable
     *
     * @return mixed|void
     */
    public function addSign(SeafarerContract $contract, array $input)
    {
        if ('OFF' === $input['sign_type'] && ! $contract->sign_on_date && ! $contract->sign_on) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.illegal_sign_off'));
        } elseif ('OFF' === $input['sign_type'] && $input['sign_date'] <= ($contract->sign_on_date ?: $contract->sign_on->sign_date)) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.sign_off_before_sign_in'));
        }

        DB::transaction(function () use ($contract, $input) {
            $sign = $contract->signons()->create(array_only($input, ['seafarer_contract_id', 'sign_type', 'sign_date']));

            if ('ON' === $input['sign_type']) {
                $this->approveSign($contract, $sign);
            }

            return true;
        });

        return true;
    }

    /**
     * @param SeafarerContract $contract
     * @param Signon           $sign
     * @param null             $sign_date
     *
     * @throws \Throwable
     *
     * @return bool
     */
    public function approveSign(SeafarerContract $contract, Signon $sign, $sign_date = null)
    {
        if ('OFF' === $sign->sign_type && ! $contract->sign_on_date) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.illegal_sign_off_approve'));
        } elseif ('OFF' === $sign->sign_type && (($sign_date ?? $sign->sign_date) <= $contract->sign_on_date)) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.sign_off_before_sign_in'));
        }

        DB::transaction(function () use ($contract, $sign, $sign_date) {
            $sign_data = ['status' => 'approved'];
            if ($sign_date) {
                $sign_data['sign_date'] = $sign_date;
            }
            $sign->update($sign_data);
            if ('ON' === $sign->sign_type) {
                $contract->sign_on_date = $sign->sign_date;
            } else {
                $contract->sign_off_date = $sign->sign_date;
            }
            $contract->save();

            if ('OFF' === $sign->sign_type) {
                $this->changeStatus($contract, SeafarerContract::$archived);
            }

            return true;
        });

        return true;
    }

    /**
     * @param SeafarerContract $contract
     * @param Signon           $sign
     *
     * @throws GeneralException
     *
     * @return mixed|void
     */
    public function deleteSign(SeafarerContract $contract, Signon $sign)
    {
        if ('pending' !== $sign->status) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.sign_must_be_pending'));
        }
        if ('ON' === $sign->sign_type && ($contract->sign_off_date || $contract->sign_off)) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.delete_sign_off_first'));
        }

        if (! $sign->delete()) {
            throw new GeneralException(__('exceptions.backend.seafarer_contracts.sign_delete'));
        }

        return true;
    }
}

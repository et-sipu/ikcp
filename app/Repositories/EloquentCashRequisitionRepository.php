<?php

namespace App\Repositories;

use Exception;
use App\Models\CashRequisition;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Traits\TransmittableTrait;
use App\Repositories\Traits\SyncAttachmentsTrait;
use App\Repositories\Contracts\CashRequisitionRepository;

/**
 * Class EloquentCashRequisitionRepository.
 */
class EloquentCashRequisitionRepository extends EloquentBaseRepository implements CashRequisitionRepository
{
    use SyncAttachmentsTrait;
    use TransmittableTrait;

    /**
     * EloquentCashRequisitionRepository constructor.
     *
     * @param CashRequisition $cash_requisition
     */
    public function __construct(CashRequisition $cash_requisition)
    {
        parent::__construct($cash_requisition);
    }

//    /**
//     * @param $name
//     *
//     * @return CashRequisition
//     */
//    public function find($name)
//    {
//        /** @var CashRequisition $cash_requisition */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\CashRequisition
     */
    public function store(array $input)
    {
        /** @var CashRequisition $cash_requisition */
        $cash_requisition = $this->make(array_only($input, ['purpose', 'request_type', 'amount']));
        $cash_requisition->requester_id = auth()->user()->id;
        $cash_requisition->employee_id = auth()->user()->employee->id;

//        if ($this->find($cash_requisition->name)) {
//            throw new GeneralException(__('exceptions.backend.cash_requisitions.already_exist'));
//        }

        DB::transaction(function () use ($cash_requisition, $input) {
            if (! $cash_requisition->save()) {
                throw new GeneralException(__('exceptions.backend.cash_requisitions.create'));
            }

            $this->sync_attachments($cash_requisition, $input['attachments'] ? $input['attachments'] : []);

            if (isset($input['invoice'])) {
                $cash_requisition->addMedia($input['invoice'])->toMediaCollection('invoice');
            }

            return true;
        });

        return $cash_requisition;
    }

    /**
     * @param CashRequisition $cash_requisition
     * @param array           $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\CashRequisition
     */
    public function update(CashRequisition $cash_requisition, array $input)
    {
//        if (($existingCashRequisition = $this->find($cash_requisition->name))
//          && $existingCashRequisition->id !== $cash_requisition->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.cash_requisitions.already_exist'));
//        }

        if ('hod_approving' === $cash_requisition->status) {
            $input = array_only($input, ['purpose', 'amount', 'request_type', 'attachments']);
        } elseif ('finance_approving' === $cash_requisition->status) {
            $input = array_only($input, ['approved_amount', 'cash_advance_taken', 'cash_advance_taken_date', 'total_receipt_returned', 'total_receipt_returned_date', 'outstanding_ca']);
        } elseif ('proofing' === $cash_requisition->status) {
            $input = array_only($input, ['receipts']);
        } elseif ('releasing' === $cash_requisition->status) {
            $input = array_only($input, ['remarks']);
        }

        DB::transaction(function () use ($cash_requisition, $input) {
            if (! $cash_requisition->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.cash_requisitions.update'));
            }

            $this->sync_attachments($cash_requisition, isset($input['attachments']) && $input['attachments'] ? $input['attachments'] : []);

            $this->sync_attachments($cash_requisition, isset($input['receipts']) && $input['receipts'] ? $input['receipts'] : [], 'receipts');

            if (isset($input['invoice'])) {
                if ($cash_requisition->getMedia('invoice')->first()) {
                    $cash_requisition->deleteMedia($cash_requisition->getMedia('invoice')->first());
                }

                $cash_requisition->addMedia($input['invoice'])->toMediaCollection('invoice');
            }

            return true;
        });

        return $cash_requisition;
    }

    /**
     * @param CashRequisition $cash_requisition
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(CashRequisition $cash_requisition)
    {
        if (! $cash_requisition->delete()) {
            throw new GeneralException(__('exceptions.backend.cash_requisitions.delete'));
        }

        return true;
    }

    public function get_validation_rules($transition)
    {
        $rules = [];

        if (preg_match('/finance_approved$/', $transition)) {
            $rules = [
                'approved_amount'             => 'required|numeric',
                'cash_advance_taken'          => 'required|numeric',
                'cash_advance_taken_date'     => 'nullable|date_format:Y-m-d',
                'total_receipt_returned'      => 'required|numeric',
                'total_receipt_returned_date' => 'nullable|date_format:Y-m-d',
                'outstanding_ca'              => 'nullable|numeric',
            ];
        }

        if (preg_match('/proofed/', $transition)) {
            $rules = [
                'receipts.*.file' => 'required|mimes:jpeg,jpg,png,gif,pdf',
            ];
        }

        return $rules;
    }

    /**
     * @param PurchaseRequisition $purchase_requisition
     * @param $transition
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function check_possibility(CashRequisition $cash_requisition, $transition)
    {
        if (preg_match('/proofed/', $transition)) {
            if (! $cash_requisition->getMedia('receipts')->count()) {
                throw new GeneralException(__('exceptions.backend.cash_requisitions.upload_receipts'));
            }
        }

        return false;
    }
}

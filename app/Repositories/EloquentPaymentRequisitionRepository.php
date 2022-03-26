<?php

namespace App\Repositories;

use Exception;
use App\Models\PaymentRequisition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Exceptions\GeneralException;
use App\Repositories\Traits\TransmittableTrait;
use App\Repositories\Traits\SyncAttachmentsTrait;
use App\Repositories\Contracts\PaymentRequisitionRepository;

/**
 * Class EloquentPaymentRequisitionRepository.
 */
class EloquentPaymentRequisitionRepository extends EloquentBaseRepository implements PaymentRequisitionRepository
{
    use SyncAttachmentsTrait;
    use TransmittableTrait;

    /**
     * EloquentPaymentRequisitionRepository constructor.
     *
     * @param PaymentRequisition $payment_requisition
     */
    public function __construct(PaymentRequisition $payment_requisition)
    {
        parent::__construct($payment_requisition);
    }

//    /**
//     * @param $name
//     *
//     * @return PaymentRequisition
//     */
//    public function find($name)
//    {
//        /** @var PaymentRequisition $payment_requisition */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\PaymentRequisition
     */
    public function store(array $input)
    {
        /** @var PaymentRequisition $payment_requisition */
        $payment_requisition = $this->make(array_only($input, ['released_to', 'title', 'details', 'remarks', 'project', 'supplier', 'new_outstanding_invoices', 'currency']));
        $payment_requisition->requester_id = auth()->user()->id;
        $payment_requisition->employee_id = auth()->user()->employee->id;

//        if ($this->find($payment_requisition->name)) {
//            throw new GeneralException(__('exceptions.backend.payment_requisitions.already_exist'));
//        }

        DB::transaction(function () use ($payment_requisition, $input) {
            if (! $payment_requisition->save()) {
                throw new GeneralException(__('exceptions.backend.payment_requisitions.create'));
            }

            $this->sync_attachments($payment_requisition, isset($input['attachments']) && $input['attachments'] ? $input['attachments'] : [],'attachments',[],isset($input['source']) && $input['source'] == 'flight_reservation' ? true : false);

            if (isset($input['payment_voucher'])) {
                $payment_requisition->addMedia($input['payment_voucher'])->toMediaCollection('payment_voucher');
            }

            return true;
        });

        return $payment_requisition;
    }

    /**
     * @param PaymentRequisition $payment_requisition
     * @param array              $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\PaymentRequisition
     */
    public function update(PaymentRequisition $payment_requisition, array $input)
    {
//        if (($existingPaymentRequisition = $this->find($payment_requisition->name))
//          && $existingPaymentRequisition->id !== $payment_requisition->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.payment_requisitions.already_exist'));
//        }

        if ('hod_approving' === $payment_requisition->status) {
            $input = array_only($input, ['released_to', 'title', 'details', 'remarks', 'project', 'supplier', 'new_outstanding_invoices', 'currency', 'attachments']);
        } elseif ('finance_approving' === $payment_requisition->status) {
            $input = array_only($input, ['last_pv_no', 'last_payment_amount', 'last_payment_date', 'old_outstanding_invoices', 'payment']);
        } elseif ('dec_approving' === $payment_requisition->status) {
            $input = array_only($input, ['approved_payment']);
        } elseif ('releasing' === $payment_requisition->status) {
            $input = array_only($input, ['cheque_no', 'cheque_bank', 'cheque_date', 'payment_voucher']);
        } else {
            $input = [];
        }

        DB::transaction(function () use ($payment_requisition, $input) {
            if (! $payment_requisition->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.payment_requisitions.update'));
            }

            if ('hod_approving' === $payment_requisition->status) {
                $this->sync_attachments($payment_requisition, isset($input['attachments']) && $input['attachments'] ? $input['attachments'] : []);
            }

            if (isset($input['payment_voucher'])) {
                if ($payment_requisition->getMedia('payment_voucher')->first()) {
                    $payment_requisition->deleteMedia($payment_requisition->getMedia('payment_voucher')->first());
                }

                $payment_requisition->addMedia($input['payment_voucher'])->toMediaCollection('payment_voucher');
            }

            return true;
        });

        return $payment_requisition;
    }

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(PaymentRequisition $payment_requisition)
    {
        if (! $payment_requisition->delete()) {
            throw new GeneralException(__('exceptions.backend.payment_requisitions.delete'));
        }

        return true;
    }

    /**
     * @param PaymentRequisition $payment_requisition
     * @param array              $data
     *
     * @return mixed|void
     */
    public function generate_payment_voucher(PaymentRequisition $payment_requisition, $data = [])
    {
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('templates.payment_voucher', compact('payment_requisition', 'data'));

        return $pdf;
    }

    public function get_validation_rules($transition)
    {
        $rules = [];

//        if ('finance_approved' === $transition) {
        if (preg_match('/finance_approved$/', $transition)) {
            $rules = [
                'last_pv_no'               => 'nullable|numeric',
                'last_payment_amount'      => 'nullable|numeric',
                'last_payment_date'        => 'nullable|date_format:Y-m-d',
                'old_outstanding_invoices' => 'nullable|numeric',
                'payment'                  => 'required|numeric|min:1',
            ];
        } elseif ('dec_approved' === $transition) {
            $rules = [
                'approved_payment'         => 'required|numeric|min:1',
            ];
        } elseif ('closing' === $transition) {
            $rules = [
                'cheque_no'       => 'required',
                'cheque_bank'     => 'required',
                'cheque_date'     => 'required|date_format:Y-m-d',
                'payment_voucher' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
            ];
        }

        return $rules;
    }

    public function get_validation_messages($transition)
    {
        return [
            'attachments.*.file.mimes'          => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
            'new_outstanding_invoices.required' => 'The Payment field is required.',
            'new_outstanding_invoices.numeric'  => 'The Payment field must be a number.',
            'new_outstanding_invoices.min'      => 'The Payment must be at least 1.',
            'payment.required'                  => 'The Proposed Payment field is required.',
            'payment.numeric'                   => 'The Proposed Payment must be a number.',
            'payment.min'                       => 'The Proposed Payment must be at least 1.',
        ];
    }

    /**
     * @param PurchaseRequisition $purchase_requisition
     * @param $transition
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function check_possibility(PaymentRequisition $payment_requisition, $transition)
    {
        if (preg_match('/closing/', $transition)) {
            if (! $payment_requisition->payment_voucher_url) {
                throw new GeneralException(__('exceptions.backend.payment_requisitions.upload_payment_voucher'));
            }
        }

        return false;
    }
}

<?php

namespace App\Transformers;

use App\Models\PaymentRequisition;
use League\Fractal\TransformerAbstract;

class PaymentRequisitionTransformer extends TransformerAbstract
{
    /**
     * @var bool
     */
    private $forExport;

    /**
     * PaymentRequisitionTransformer constructor.
     *
     * @param bool $forExport
     */
    public function __construct($forExport = false)
    {
        $this->forExport = $forExport;
    }

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @return array
     */
    public function transform(PaymentRequisition $payment_requisition)
    {
        if ($this->forExport) {
            return $this->for_export($payment_requisition);
        }

        $transitions = [];
        foreach ($payment_requisition->workflow_transitions() as $transition) {
            array_push($transitions, $transition->getName());
        }

        return [
            'id'                                => $payment_requisition->id,
            'released_to'                       => $payment_requisition->released_to,
            'requester_id'                      => $payment_requisition->requester->id,
//            'requester_name'                    => $payment_requisition->requester->name,
            'requester_name'                    => $payment_requisition->employee ? $payment_requisition->employee->full_name : '',
            'title'                             => $payment_requisition->title,
            'details'                           => $payment_requisition->details,
            'remarks'                           => $payment_requisition->remarks,
            'project'                           => $payment_requisition->project,
            'supplier'                          => $payment_requisition->supplier,
            'last_pv_no'                        => $payment_requisition->last_pv_no,
            'last_payment_amount'               => $payment_requisition->last_payment_amount,
            'last_payment_date'                 => $payment_requisition->last_payment_date,
            'old_outstanding_invoices'          => $payment_requisition->old_outstanding_invoices,
            'new_outstanding_invoices'          => $payment_requisition->new_outstanding_invoices,
            'currency'                          => $payment_requisition->currency,
            'payment'                           => $payment_requisition->payment,
            'approved_payment'                  => $payment_requisition->approved_payment,
            'cheque_no'                         => $payment_requisition->cheque_no,
            'cheque_bank'                       => $payment_requisition->cheque_bank,
            'cheque_date'                       => $payment_requisition->cheque_date,
            'status'                            => $payment_requisition->status,
            'available_transitions'             => $transitions,
            'transitions'                        => $payment_requisition->transitions->sortBy('id')->pluck('in_date', 'status')->toArray(),
            'attachments'                       => $payment_requisition->attachments_info,
            'payment_voucher_url'               => $payment_requisition->payment_voucher_url,
            'created_at'                        => $payment_requisition->created_at->format('Y-m-d'),
            'can_edit'                          => $payment_requisition->can_edit,
            'can_pass'                          => $payment_requisition->can_pass,
            'can_pass_urgently'                 => $payment_requisition->can_pass_urgently,
            'can_view'                          => $payment_requisition->can_view,
            'can_delete'                        => $payment_requisition->can_delete,
            'can_print'                         => $payment_requisition->can_print,
        ];
    }

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @return array
     */
    public function for_export(PaymentRequisition $payment_requisition)
    {
        return [
            'id'                                => $payment_requisition->id,
            'released_to'                       => $payment_requisition->released_to,
            'requester'                         => $payment_requisition->employee->full_name,
            'title'                             => $payment_requisition->title,
            'details'                           => $payment_requisition->details,
            'remarks'                           => $payment_requisition->remarks,
            'project'                           => $payment_requisition->project,
            'supplier'                          => $payment_requisition->supplier,
            'last_pv_no'                        => $payment_requisition->last_pv_no,
            'last_payment_amount'               => $payment_requisition->last_payment_amount,
            'last_payment_date'                 => $payment_requisition->last_payment_date,
            'old_outstanding_invoices'          => $payment_requisition->old_outstanding_invoices,
            'new_outstanding_invoices'          => $payment_requisition->new_outstanding_invoices,
            'currency'                          => $payment_requisition->currency,
            'payment'                           => $payment_requisition->payment,
            'approved_payment'                  => $payment_requisition->approved_payment,
            'cheque_no'                         => $payment_requisition->cheque_no,
            'cheque_bank'                       => $payment_requisition->cheque_bank,
            'cheque_date'                       => $payment_requisition->cheque_date,
            'status'                            => __('labels.backend.payment_requisitions.states.'.$payment_requisition->status),
            'creation_date'                     => $payment_requisition->created_at->format('Y-m-d'),
        ];
    }
}

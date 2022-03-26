<?php

namespace App\Transformers;

use App\Models\CashRequisition;
use League\Fractal\TransformerAbstract;

class CashRequisitionTransformer extends TransformerAbstract
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
     * @param CashRequisition $cash_requisition
     *
     * @return array
     */
    public function transform(CashRequisition $cash_requisition)
    {
        if ($this->forExport) {
            return $this->for_export($cash_requisition);
        }

        $transitions = [];
        foreach ($cash_requisition->workflow_transitions() as $transition) {
            array_push($transitions, $transition->getName());
        }

        return [
            'id'                                 => $cash_requisition->id,
            'requester_id'                       => $cash_requisition->requester_id,
            'requester_name'                     => $cash_requisition->employee ? $cash_requisition->employee->full_name : '',
            'department_name'                    => $cash_requisition->employee && $cash_requisition->employee->department ? $cash_requisition->employee->department->name : '',
            'purpose'                            => $cash_requisition->purpose,
            'request_type'                       => $cash_requisition->request_type,
            'amount'                             => $cash_requisition->amount,
            'approved_amount'                    => $cash_requisition->approved_amount,
            'cash_advance_taken'                 => $cash_requisition->cash_advance_taken,
            'cash_advance_taken_date'            => $cash_requisition->cash_advance_taken_date,
            'total_receipt_returned'             => $cash_requisition->total_receipt_returned,
            'total_receipt_returned_date'        => $cash_requisition->total_receipt_returned_date,
            'outstanding_ca'                     => $cash_requisition->outstanding_ca,
            'attachments'                        => $cash_requisition->attachments_info,
            'receipts'                           => $cash_requisition->receipts_info,
            'remarks'                            => $cash_requisition->remarks,
            'status'                             => $cash_requisition->status,
            'available_transitions'              => $transitions,
            'transitions'                        => $cash_requisition->transitions->sortBy('id')->pluck('in_date', 'status')->toArray(),
            'created_at'                         => $cash_requisition->created_at->format('Y-m-d'),
            'can_edit'                           => $cash_requisition->can_edit,
            'can_pass'                           => $cash_requisition->can_pass,
            'can_pass_urgently'                  => $cash_requisition->can_pass_urgently,
            'can_view'                           => $cash_requisition->can_view,
            'can_delete'                         => $cash_requisition->can_delete,
            'can_print'                          => $cash_requisition->can_print,
        ];
    }

    /**
     * @param CashRequisition $cash_requisition
     *
     * @return array
     */
    public function for_export(CashRequisition $cash_requisition)
    {
        return [
            'id'                                => $cash_requisition->id,
            'requester_id'                      => $cash_requisition->requester_id,
            'requester'                         => $cash_requisition->employee->full_name,
            'department'                        => $cash_requisition->employee->department->name,
            'purpose'                           => $cash_requisition->purpose,
            'request_type'                      => __('validation.attributes.'.$cash_requisition->request_type),
            'amount'                            => $cash_requisition->amount,
            'approved_amount'                   => $cash_requisition->approved_amount,
            'cash_advance_taken'                => $cash_requisition->cash_advance_taken,
            'cash_advance_taken_date'           => $cash_requisition->cash_advance_taken_date,
            'total_receipt_returned'            => $cash_requisition->total_receipt_returned,
            'total_receipt_returned_date'       => $cash_requisition->total_receipt_returned_date,
            'outstanding_ca'                    => $cash_requisition->outstanding_ca,
            'status'                            => __('labels.backend.cash_requisitions.states.'.$cash_requisition->status),
            'creation_date'                     => $cash_requisition->created_at->format('Y-m-d'),
        ];
    }
}

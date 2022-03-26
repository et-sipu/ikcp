<?php

namespace App\Repositories\Contracts;

use App\Models\PaymentRequisition;
use App\Models\Contracts\Workflowable;

/**
 * Interface PaymentRequisitionlRepository.
 */
interface PaymentRequisitionRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return PaymentRequisition
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param PaymentRequisition $payment_requisition
     * @param array              $input
     *
     * @return mixed
     */
    public function update(PaymentRequisition $payment_requisition, array $input);

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @return mixed
     */
    public function destroy(PaymentRequisition $payment_requisition);

    /**
     * @param Workflowable $cash_requisition
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Workflowable $payment_requisition, $new_status);

    /**
     * @param PaymentRequisition $payment_requisition
     * @param array              $data
     *
     * @return mixed
     */
    public function generate_payment_voucher(PaymentRequisition $payment_requisition, $data = []);
}

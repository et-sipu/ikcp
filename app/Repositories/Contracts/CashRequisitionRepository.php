<?php

namespace App\Repositories\Contracts;

use App\Models\CashRequisition;
use App\Models\Contracts\Workflowable;

/**
 * Interface CashRequisitionlRepository.
 */
interface CashRequisitionRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return CashRequisition
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param CashRequisition $cash_requisition
     * @param array           $input
     *
     * @return mixed
     */
    public function update(CashRequisition $cash_requisition, array $input);

    /**
     * @param CashRequisition $cash_requisition
     *
     * @return mixed
     */
    public function destroy(CashRequisition $cash_requisition);

    /**
     * @param Workflowable $cash_requisition
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Workflowable $cash_requisition, $new_status);
}

<?php

namespace App\Http\Controllers\Backend;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use OwenIt\Auditing\Models\Audit;
use App\Transformers\AuditTransformer;

class AuditController extends BackendController
{
    public function search(Request $request)
    {
        $query = (new Audit())->newQuery();
        $query->with(['user']);
        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->events) && \is_array($extraSearch->events) && \count($extraSearch->events) > 0) {
            $query->whereIn('event', $extraSearch->events);
        }

        if (isset($extraSearch->selected_users)) {
            if (isset($extraSearch->selected_users) && \is_array($extraSearch->selected_users) && \count($extraSearch->selected_users) > 0) {
                $user_id = array_map(function ($value) {
                    return $value->id;
                }, $extraSearch->selected_users);
                $query->whereIn('user_id', $user_id);
            }
        }
        if (isset($extraSearch->start_date) && $extraSearch->start_date) {
            $query->whereDate('created_at', '>=', $extraSearch->start_date);
        }
        if (isset($extraSearch->end_date) && $extraSearch->end_date) {
            $query->whereDate('created_at', '<=', $extraSearch->end_date);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['user.user_name', 'event', 'user_type']);

        return $requestSearchQuery->result(new AuditTransformer());
    }

    public function getPaymentRequisitionHistory(Audit $payment_requisition)
    {
        $auditable_id = $payment_requisition->id;
        $query = (new Audit())->newQuery();
        $query->where('auditable_id', $auditable_id);
        $query->where('auditable_type', 'PaymentRequisition');
        $query->where('event', 'updated');
        $query->where('old_values', 'LIKE', '%new_outstanding_invoices%');
        $query->with('user');

        $firstdata = Fractal::create()->collection($query->get(), new AuditTransformer())
            ->toArray();

        $firstdata = array_map(function ($item) {
            return ['created_at' => $item['created_at'], 'username' => $item['user_name'], 'old' => $item['old_values']['new_outstanding_invoices'], 'new' => $item['new_values']['new_outstanding_invoices']];
        }, $firstdata);

        return $firstdata;
    }

    public function getCashRequisitionHistory(Audit $cash_requisition)
    {
        $auditable_id = $cash_requisition->id;
        $query = (new Audit())->newQuery();
        $query->where('auditable_id', $auditable_id);
        $query->where('auditable_type', 'CashRequisition');
        $query->where('event', 'updated');
        $query->where('old_values', 'LIKE', '%\"amount\"%');
        $query->with('user');

        $firstdata = Fractal::create()->collection($query->get(), new AuditTransformer())
            ->toArray();
        $firstdata = array_map(function ($item) {
            return ['created_at' => $item['created_at'], 'username' => $item['user_name'], 'old' => $item['old_values']['amount'], 'new' => $item['new_values']['amount']];
        }, $firstdata);

        return $firstdata;
    }
}

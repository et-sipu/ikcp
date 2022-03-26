<?php

namespace App\Http\Controllers\Backend;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Models\PaymentRequisition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\PaymentRequisitionTransformer;
use App\Http\Requests\GeneratePaymentVoucherRequest;
use App\Http\Requests\StorePaymentRequisitionRequest;
use App\Http\Requests\UpdatePaymentRequisitionRequest;
use App\Repositories\Contracts\PaymentRequisitionRepository;

class PaymentRequisitionController extends BackendController
{
    /**
     * @var PaymentRequisitionRepository
     */
    protected $payment_requisitions;

    protected $repo_name = 'payment_requisitions';

    /**
     * Create a new controller instance.
     *
     * @param PaymentRequisitionRepository $payment_requisitions
     */
    public function __construct(PaymentRequisitionRepository $payment_requisitions)
    {
        $this->payment_requisitions = $payment_requisitions;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array|\Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['title', 'project', 'supplier', 'requester.name']);

        return $requestSearchQuery->result(new PaymentRequisitionTransformer());
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $this->authorize('export payment requisitions');

        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query);

        return $requestSearchQuery->export('payment_requisitions', new PaymentRequisitionTransformer(true));
    }

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(PaymentRequisition $payment_requisition)
    {
        $this->authorize('view', $payment_requisition);

        return Fractal::create()->item($payment_requisition, new PaymentRequisitionTransformer());
    }

    /**
     * @param StorePaymentRequisitionRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StorePaymentRequisitionRequest $request)
    {
        $this->authorize('create payment requisitions');

        $this->payment_requisitions->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.payment_requisitions.created'));
    }

    /**
     * @param PaymentRequisition              $payment_requisition
     * @param UpdatePaymentRequisitionRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(PaymentRequisition $payment_requisition, UpdatePaymentRequisitionRequest $request)
    {
        $this->authorize('edit', $payment_requisition);

        $this->payment_requisitions->update($payment_requisition, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.payment_requisitions.updated'));
    }

    /**
     * @param PaymentRequisition $payment_requisition
     * @param Request            $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(PaymentRequisition $payment_requisition, Request $request)
    {
        $this->authorize('delete', $payment_requisition);

        $this->payment_requisitions->destroy($payment_requisition);

        return $this->redirectResponse($request, __('alerts.backend.payment_requisitions.deleted'));
    }

    /**
     * @param Request            $request
     * @param PaymentRequisition $payment_requisition
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, PaymentRequisition $payment_requisition, $status)
    {
        $this->authorize('pass', [$payment_requisition, true]);

        $this->payment_requisitions->changeStatus($payment_requisition, $status);

        return $this->redirectResponse($request, __('alerts.backend.payment_requisitions.status_changed'));
    }

    /**
     * @param PaymentRequisition $payment_requisition
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print(PaymentRequisition $payment_requisition)
    {
        $this->authorize('print', $payment_requisition);

        return view('templates.prf', ['payment_requisition' => $payment_requisition]);
    }

    /**
     * @param $constant
     *
     * @return mixed
     */
    public function getStatuses()
    {
        return Config::get('workflow.payment_requisition_process.places');
    }

    public function getHappyPath()
    {
        return Config::get('workflow.payment_requisition_process.happy_path');
    }

    /**
     * @param Request $request
     *
     * @return Builder
     */
    public function extractQuery(Request $request): Builder
    {
        $query = $this->payment_requisitions->query()->with(['requester.department', 'media', 'transitions']);
        $query->JoinWithRequester();

        if (! Gate::check('view payment requisitions')) {
//            $query->MyRequisitions(auth()->user()->id);
            $query->MyRecords(auth()->user()->employee->id, auth()->user()->id);
        }

//        $extraSearch = is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));
        $extraSearch = \is_array($request->get('extraSearch')) ? $request->get('extraSearch') : json_decode($request->get('extraSearch'));

        if (isset($extraSearch->users) && \is_array($extraSearch->users) && \count($extraSearch->users) > 0) {
            $query->whereIn('requester_id', array_pluck($extraSearch->users, 'id'));
        }

        if (isset($extraSearch->statuses) && \is_array($extraSearch->statuses) && \count($extraSearch->statuses) > 0) {
            $query->whereIn('status', array_pluck($extraSearch->statuses, 'id'));
        }

        if (isset($extraSearch->departments) && \is_array($extraSearch->departments) && \count($extraSearch->departments) > 0) {
            $department_ids = array_pluck($extraSearch->departments, 'id');
            $query->InOneOfDepartments($department_ids);
        }

        if (isset($extraSearch->start_date) && $extraSearch->start_date) {
            $query->whereDate('payment_requisitions.created_at', '>=', $extraSearch->start_date);
        }

        if (isset($extraSearch->end_date) && $extraSearch->end_date) {
            $query->whereDate('payment_requisitions.created_at', '<=', $extraSearch->end_date);
        }

        $query->select([DB::raw('users.name as requester_name'), 'payment_requisitions.*']);

        return $query;
    }

    public function generatePaymentVoucher(GeneratePaymentVoucherRequest $request, PaymentRequisition $payment_requisition)
    {
//        $this->authorize('generate vessel imo');

//        return view('templates.payment_voucher');

        $pdf = $this->payment_requisitions->generate_payment_voucher($payment_requisition, $request->all());

        return $pdf->stream();
    }
}

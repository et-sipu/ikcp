<?php

namespace App\Http\Controllers\Backend;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Models\CashRequisition;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\CashRequisitionTransformer;
use App\Http\Requests\StoreCashRequisitionRequest;
use App\Http\Requests\UpdateCashRequisitionRequest;
use App\Repositories\Contracts\CashRequisitionRepository;

class CashRequisitionController extends BackendController
{
    /**
     * @var CashRequisitionRepository
     */
    protected $cash_requisitions;

    protected $repo_name = 'cash_requisitions';

    /**
     * Create a new controller instance.
     *
     * @param CashRequisitionRepository $cash_requisitions
     */
    public function __construct(CashRequisitionRepository $cash_requisitions)
    {
        $this->cash_requisitions = $cash_requisitions;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array
     */
    public function search(Request $request)
    {
        $query = $this->extractQuery($request);
        $requestSearchQuery = new RequestSearchQuery($request, $query, ['requester.name', 'purpose']);

        return $requestSearchQuery->result(new CashRequisitionTransformer());
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
        $this->authorize('export cash requisitions');

        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query);

        return $requestSearchQuery->export('cash_requisitions', new CashRequisitionTransformer(true));
    }

    /**
     * @param CashRequisition $cash_requisition
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(CashRequisition $cash_requisition)
    {
        $this->authorize('view', $cash_requisition);

        return Fractal::create()->item($cash_requisition, new CashRequisitionTransformer());
    }

    /**
     * @param StoreCashRequisitionRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreCashRequisitionRequest $request)
    {
        $this->authorize('create cash requisitions');

        $this->cash_requisitions->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.cash_requisitions.created'));
    }

    /**
     * @param CashRequisition              $cash_requisition
     * @param UpdateCashRequisitionRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(CashRequisition $cash_requisition, UpdateCashRequisitionRequest $request)
    {
        $this->authorize('edit', $cash_requisition);

        $this->cash_requisitions->update($cash_requisition, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.cash_requisitions.updated'));
    }

    /**
     * @param CashRequisition $cash_requisition
     * @param Request         $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(CashRequisition $cash_requisition, Request $request)
    {
        $this->authorize('delete', $cash_requisition);

        $this->cash_requisitions->destroy($cash_requisition);

        return $this->redirectResponse($request, __('alerts.backend.cash_requisitions.deleted'));
    }

    /**
     * @param Request         $request
     * @param CashRequisition $cash_requisition
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, CashRequisition $cash_requisition, $status)
    {
        $this->authorize('pass', [$cash_requisition, true]);

        $this->cash_requisitions->changeStatus($cash_requisition, $status);

        return $this->redirectResponse($request, __('alerts.backend.cash_requisitions.status_changed'));
    }

    /**
     * @param CashRequisition $cash_requisition
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print(CashRequisition $cash_requisition)
    {
        $this->authorize('print', $cash_requisition);

        return view('templates.crf', ['cash_requisition' => $cash_requisition]);
    }

    public function getStatuses()
    {
        return Config::get('workflow.cash_requisition_process.places');
    }

    public function getHappyPath()
    {
        return Config::get('workflow.cash_requisition_process.happy_path');
    }

    /**
     * @param Request $request
     *
     * @return Builder
     */
    public function extractQuery(Request $request): Builder
    {
        $query = $this->cash_requisitions->query()->with(['requester.employee.department', 'media', 'transitions']);
        $query->JoinWithDepartment();

        if (! Gate::check('view cash requisitions')) {
//            $query->MyRequisitions(auth()->user()->id);
            $query->MyRecords(auth()->user()->employee->id, auth()->user()->id);
        }
        $query->select([DB::raw('users.name as requester_name'), DB::raw('departments.name as department_name'), 'cash_requisitions.*']);

//        $extraSearch = is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));
        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

        if (isset($extraSearch->departments) && \is_array($extraSearch->departments) && \count($extraSearch->departments) > 0) {
            $department_ids = array_pluck($extraSearch->departments, 'id');
            $query->InOneOfDepartments($department_ids);
        }

        if (isset($extraSearch->start_date) && $extraSearch->start_date) {
            $query->whereDate('cash_requisitions.created_at', '>=', $extraSearch->start_date);
        }
        if (isset($extraSearch->end_date) && $extraSearch->end_date) {
            $query->whereDate('cash_requisitions.created_at', '<=', $extraSearch->end_date);
        }
        if (isset($extraSearch->requesters) && \is_array($extraSearch->requesters) && \count($extraSearch->requesters) > 0) {
            $requester_ids = array_pluck($extraSearch->requesters, 'id');

            $query->whereIn('requester_id', $requester_ids);
        }
        if (isset($extraSearch->status_selected) && \is_array($extraSearch->status_selected) && \count($extraSearch->status_selected) > 0) {
            $status = array_pluck($extraSearch->status_selected, 'id');

            $query->whereIn('status', $status);
        }
        if (isset($extraSearch->selected_type) && \is_array($extraSearch->selected_type) && \count($extraSearch->selected_type) > 0) {
            //  $status = array_pluck($extraSearch->status_selected,'id');

            $query->whereIn('request_type', $extraSearch->selected_type);
        }

        return $query;
    }
}

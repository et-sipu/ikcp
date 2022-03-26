<?php

namespace App\Http\Controllers\Backend;

use App\Models\Signon;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Models\SeafarerContract;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AddSignRequest;
use App\Transformers\SeafarerContractTransformer;
use App\Http\Requests\StoreSeafarerContractRequest;
use App\Http\Requests\UpdateSeafarerContractRequest;
use App\Repositories\Contracts\SeafarerContractRepository;

class SeafarerContractController extends BackendController
{
    /**
     * @var SeafarerContractRepository
     */
    protected $contracts;

    /**
     * Create a new controller instance.
     *
     * @param SeafarerContractRepository $contracts
     */
    public function __construct(SeafarerContractRepository $contracts)
    {
        $this->contracts = $contracts;
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
        $query = $this->contracts->query()->with(['sign_on_port', 'vessel', 'seafarer.capabilitiesInfo.rank', 'media', 'sign_on', 'sign_off']);
        $query->join('employees', 'employees.id', '=', 'seafarer_contracts.seafarer_id');
        $query->leftJoin('employee_capabilities_infos', 'employee_capabilities_infos.employee_id', '=', 'employees.id');
        $query->leftJoin('ranks', 'ranks.id', '=', 'employee_capabilities_infos.rank_id');
        $query->leftJoin('vessels', 'vessels.id', '=', 'seafarer_contracts.vessel_id');
        $query->select([DB::raw('concat(employees.name,\' \',employees.surname) as seafarer_name'), 'vessels.name as vessel_name', 'ranks.name as rank_name', 'seafarer_contracts.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->statuses)) {
            $query = $query->whereIn('status', $extraSearch->statuses);
        }
        if (isset($extraSearch->ranks) && \is_array($extraSearch->ranks) && \count($extraSearch->ranks) > 0) {
            $query = $query->hasRank(array_column($extraSearch->ranks, 'id'));
        }

        if (! Gate::check('view seafarer contracts')) {
            $query->whereVesselId(auth()->user()->vessel ? auth()->user()->vessel->id : 0)->where('status', SeafarerContract::$approved);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['vessel.name', 'vessel.code', '`employees.name`-`employees.surname`', 'sign_on_port.name']);

        return $requestSearchQuery->result(new SeafarerContractTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\SeafarerContract $seafarerContract
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(SeafarerContract $seafarerContract)
    {
        $this->authorize('view', $seafarerContract);

        return Fractal::create()->item($seafarerContract, new SeafarerContractTransformer())->parseIncludes('crew_request');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSeafarerContractRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreSeafarerContractRequest $request)
    {
        $this->authorize('create seafarer contracts');

        $this->contracts->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.created'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSeafarerContractRequest $request
     * @param \App\Models\SeafarerContract  $seafarerContract
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSeafarerContractRequest $request, SeafarerContract $seafarerContract)
    {
        $this->authorize('update', $seafarerContract);

        $this->contracts->update($seafarerContract, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SeafarerContract $seafarerContract
     * @param Request                      $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeafarerContract $seafarerContract, Request $request)
    {
        $this->authorize('delete seafarer contracts');

        $this->contracts->destroy($seafarerContract);

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.deleted'));
    }

    public function approve(SeafarerContract $seafarerContract, Request $request)
    {
        $this->authorize('approve seafarer contracts');

        $this->contracts->changeStatus($seafarerContract, SeafarerContract::$approved);

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.approved'));
    }

    public function print(SeafarerContract $seafarerContract)
    {
        $pdf = App::make('dompdf.wrapper');
        $seafarerContract->load([
            'seafarer.contactInfo',
            'seafarer.financialInfo',
            'seafarer.capabilitiesInfo',
            'seafarer.documents',
        ]);

        $pdf->loadView('templates.seafarer_contract', compact('seafarerContract'));

        return $pdf->stream();
    }

    public function print_sea(SeafarerContract $seafarerContract)
    {
        $pdf = App::make('dompdf.wrapper');
        $seafarerContract->load([
            'seafarer.contactInfo',
            'seafarer.financialInfo',
            'seafarer.capabilitiesInfo',
            'seafarer.documents',
        ]);

//        return view('templates.seafarer_contract_sea',compact('seafarerContract'));
        $pdf->loadView('templates.seafarer_contract_sea', compact('seafarerContract'));

        return $pdf->stream();
    }

    public function print_prop(SeafarerContract $seafarerContract)
    {
        $pdf = App::make('dompdf.wrapper');
        $seafarerContract->load([
            'seafarer.contactInfo',
            'seafarer.financialInfo',
            'seafarer.capabilitiesInfo',
            'seafarer.documents',
        ]);

//        return view('templates.seafarer_contract_sea',compact('seafarerContract'));
        $pdf->loadView('templates.seafarer_contract_prop', compact('seafarerContract'));

        return $pdf->stream();
    }

    /**
     * @param SeafarerContract $seafarerContract
     * @param AddSignRequest   $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function signons(SeafarerContract $seafarerContract, AddSignRequest $request)
    {
        $this->authorize('add_sign', $seafarerContract);

        $this->contracts->addSign($seafarerContract, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.signs.created'));
    }

    /**
     * @param SeafarerContract $seafarerContract
     * @param Signon           $sign
     * @param Request          $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function approve_sign(SeafarerContract $seafarerContract, Signon $sign, Request $request)
    {
        $this->authorize('approve seafarer signs');

        $this->contracts->approveSign($seafarerContract, $sign, $request->get('sign_date'));

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.signs.approved'));
    }

    /**
     * @param SeafarerContract $seafarerContract
     * @param Signon           $sign
     * @param Request          $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_sign(SeafarerContract $seafarerContract, Signon $sign, Request $request)
    {
        $this->authorize('delete_sign', $seafarerContract);

        $this->contracts->deleteSign($seafarerContract, $sign);

        return $this->redirectResponse($request, __('alerts.backend.seafarer_contracts.signs.deleted'));
    }

    public function contract_preview(StoreSeafarerContractRequest $request)
    {
        //$this->authorize('preview contract');

        $contract = $this->contracts->store($request->all(), true);
        $contract->is_dummy = true;

        return $this->print($contract);
    }
}

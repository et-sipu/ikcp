<?php

namespace App\Http\Controllers\Backend;

use App\Models\VesselInsurance;
use App\Transformers\VesselInsuranceTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreVesselInsuranceRequest;
use App\Http\Requests\UpdateVesselInsuranceRequest;
use App\Repositories\Contracts\VesselInsuranceRepository;
use Spatie\Fractal\Fractal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VesselInsuranceExpiry;

class VesselInsuranceController extends BackendController
{

    /**
     * @var VesselInsuranceRepository
     */
    protected $vessel_insurances;

    /**
     * Create a new controller instance.
     *
     * @param VesselInsuranceRepository $vessel_insurances
     */
    public function __construct(VesselInsuranceRepository $vessel_insurances)
    {
        $this->vessel_insurances = $vessel_insurances;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     *
     */
    public function search(Request $request)
    {
        $query = $this->vessel_insurances->query()->with([]);
        $extraSearch = json_decode($request->get('extraSearch'));
        if (isset($extraSearch->vessel_id) && is_numeric($extraSearch->vessel_id)) {
            $vessel_id = $extraSearch->vessel_id;
            $query->where(static function($query) use($vessel_id){

            $query->whereRaw('JSON_SEARCH(json_extract(vessels,"$[*].vessel_covered.id"),"one",?) is not null',[$vessel_id]);
            });

        }
        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new VesselInsuranceTransformer());

    }

    /**
     * @param VesselInsurance $vessel_insurance
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VesselInsurance $vessel_insurance)
    {
        // $this->authorize('view vessel insurances');

        return Fractal::create()->item($vessel_insurance, new VesselInsuranceTransformer());
    }

    /**
     * @param StoreVesselInsuranceRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreVesselInsuranceRequest $request)
    {
        // $this->authorize('create vessel insurances');

        $this->vessel_insurances->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.vessel_insurances.created'));
    }

    /**
     * @param VesselInsurance $vessel_insurance
     * @param UpdateVesselInsuranceRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VesselInsurance $vessel_insurance, UpdateVesselInsuranceRequest $request)
    {
        // $this->authorize('edit vessel insurances');

        $this->vessel_insurances->update($vessel_insurance, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.vessel_insurances.updated'));
    }

    /**
     * @param VesselInsurance $vessel_insurance
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(VesselInsurance $vessel_insurance, Request $request)
    {
//         $this->authorize('delete vessel insurances');

        $this->vessel_insurances->destroy($vessel_insurance);

        return $this->redirectResponse($request, __('alerts.backend.vessel_insurances.deleted'));
    }

    public function vessel_insurance_expiration(){
        $insurances= VesselInsurance::all();
        $current_month = new Carbon(now());
        $two_month =now()->addMonth(2);

        $expired=[];
        $warning=[];
        $exp_covered=[];
        $warning_covered=[];
        if ($insurances) {
            foreach ($insurances as $insurance) {
                $expiry_date = new Carbon( $insurance->expiry_date);

                if ($expiry_date <= $current_month->toDateString()) {
                    array_push($expired, $insurance->toArray());
                    foreach ($insurance->vessels as $vessel) {
                        array_push($exp_covered, $vessel);
                    }
                } elseif ($expiry_date <= $two_month->toDateString()) {
                    array_push($warning, $insurance->toArray());
                    foreach ($insurance->vessels as $vessel) {
                        array_push($warning_covered, $vessel);
                    }
                }
            }
//            dd($expired, $warning, $insurances);
            Mail::to('homam@inaikiara.com.my')
                ->send(new VesselInsuranceExpiry($expired, $warning));
        }
    }
}
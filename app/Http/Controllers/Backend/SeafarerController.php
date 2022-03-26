<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Vessel;
use App\Models\Seafarer;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\DB;
use App\Transformers\AuditTransformer;
use App\Transformers\SeafarerTransformer;
use App\Http\Requests\StoreSeafarerRequest;
use App\Http\Requests\UpdateSeafarerRequest;
use App\Repositories\Contracts\SeafarerRepository;

class SeafarerController extends BackendController
{
    /**
     * @var SeafarerRepository
     */
    protected $seafarers;

    protected $repo_name = 'seafarers';

    /**
     * Create a new controller instance.
     *
     * @param SeafarerRepository $seafarers
     */
    public function __construct(SeafarerRepository $seafarers)
    {
        $this->seafarers = $seafarers;
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
        $query = $this->seafarers->query()->with(['capabilitiesInfo.rank', 'documents']);

        $query->select([DB::raw('concat(name,\' \',surname) as full_name'), 'employees.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

//        if(isset($extraSearch->age) && is_array($extraSearch->age)){
//            $query = $query->ageBetween($extraSearch->age);
//        }
//        if(isset($extraSearch->seniority) && is_array($extraSearch->seniority)){
//            $query = $query->seniorityBetween($extraSearch->seniority);
//        }
        if (isset($extraSearch->nationalities) && \is_array($extraSearch->nationalities) && \count($extraSearch->nationalities) > 0) {
            $nationalities = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->nationalities);
            $query = $query->hasNationalityOf($nationalities);
        }
        if (isset($extraSearch->ranks) && \is_array($extraSearch->ranks) && \count($extraSearch->ranks) > 0) {
            $query = $query->hasRank(array_column($extraSearch->ranks, 'id'));
        }
        if (isset($extraSearch->coc_certificate_types) && \is_array($extraSearch->coc_certificate_types) && \count($extraSearch->coc_certificate_types) > 0) {
            $query = $query->hasCOC($extraSearch->coc_certificate_types);
        }
        if (isset($extraSearch->security_courses) && \is_array($extraSearch->security_courses) && \count($extraSearch->security_courses) > 0) {
            $query = $query->hasSecurityCources($extraSearch->security_courses);
        }
        if (isset($extraSearch->other_courses) && \is_array($extraSearch->other_courses) && \count($extraSearch->other_courses) > 0) {
            $query = $query->hasCources($extraSearch->other_courses);
        }
        if (isset($extraSearch->available_for_contracting)) {
            $query = $query->availableForContracting($extraSearch->available_for_contracting);
        }
        if (isset($extraSearch->passport_no) && ! empty($extraSearch->passport_no)) {
            $query = $query->withPassport($extraSearch->passport_no);
        }
        if (isset($extraSearch->smc_reg_no) && ! empty($extraSearch->smc_reg_no)) {
            $query = $query->withSMC($extraSearch->smc_reg_no);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['name-surname', 'nric_no', 'date_of_birth', 'place_of_birth']);

        return $requestSearchQuery->result(new SeafarerTransformer());
    }

    /**
     * @param Seafarer $seafarer
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Seafarer $seafarer)
    {
        $this->authorize('view seafarers');

        return Fractal::create()->item($seafarer, new SeafarerTransformer(true));
    }

    /**
     * @param StoreSeafarerRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreSeafarerRequest $request)
    {
        $this->authorize('create seafarers');

        $this->seafarers->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.seafarers.created'));
    }

    /**
     * @param Seafarer              $seafarer
     * @param UpdateSeafarerRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Seafarer $seafarer, UpdateSeafarerRequest $request)
    {
        $this->authorize('update', $seafarer);

        $this->seafarers->update($seafarer, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.seafarers.updated'));
    }

    /**
     * @param Seafarer $seafarer
     * @param Request  $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Seafarer $seafarer, Request $request)
    {
        $this->authorize('delete seafarers');

        $this->seafarers->destroy($seafarer);

        return $this->redirectResponse($request, __('alerts.backend.seafarers.deleted'));
    }

    /**
     * @param Vessel  $vessel
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function getOnBoardSeafarers(Vessel $vessel)
    {
        $this->authorize('view seafarers');

        return $vessel->onboard_seafarers->load('capabilitiesInfo')->map(function ($seafarer) {
            $seafarer->scheduled_sign_off_date = Carbon::createFromFormat('Y-m-d', $seafarer->ongoing_contract->scheduled_sign_off_date)->addDay()->format('Y-m-d');
            $seafarer->rank = $seafarer->capabilitiesInfo->rank ? array_only($seafarer->capabilitiesInfo->rank->toArray(), ['id', 'name']) : [];
            $seafarer->name = $seafarer->full_name;
            $data = $seafarer->toArray();

            return collect($data)
                ->only(['id', 'name', 'scheduled_sign_off_date', 'rank'])
                ->all();
        });
    }

    /**
     * @param Seafarer $seafarer
     * @param Request  $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function blacklist(Seafarer $seafarer, Request $request)
    {
        $this->authorize('blacklist seafarers');

        $this->seafarers->blacklist($seafarer, $request->get('comment'));

        return $this->redirectResponse($request, __('alerts.backend.seafarers.blacklisted'));
    }

    /**
     * @param Seafarer $seafarer
     * @param Request  $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function whitelist(Seafarer $seafarer, Request $request)
    {
        $this->authorize('whitelist seafarers');

        $this->seafarers->whitelist($seafarer, $request->get('comment'));

        return $this->redirectResponse($request, __('alerts.backend.seafarers.whitelisted'));
    }

    /**
     * @param Request $request
     * @param $auditable_id
     *
     * @return array
     */
    public function getHistory(Request $request, $auditable_id)
    {
        /** @var Seafarer $seafarer */
        $seafarer = $this->{$this->repo_name}->query()->find($auditable_id);
        $relations = [];
        $relations['EmployeeContactInfo'] = $seafarer->contactInfo->id;
        $relations['EmployeeFinancialInfo'] = $seafarer->financialInfo->id;
        $relations['EmployeeCapabilitiesInfo'] = $seafarer->capabilitiesInfo->id;
        $relations['Document'] = $seafarer->documents->pluck('id')->toArray();

        $query = Audit::query();
        foreach ($relations as $type => $id) {
            if (\is_array($id)) {
                foreach ($id as $item_id) {
                    $query->orWhere(function ($q) use ($type, $item_id) {
                        return $q->where('auditable_id', $item_id)->where('auditable_type', $type);
                    });
                }
            } else {
                $query->orWhere(function ($q) use ($id, $type) {
                    return $q->where('auditable_id', $id)->where('auditable_type', $type);
                });
            }
        }
        $query->orWhere(function ($q) use ($auditable_id) {
            return $q->where('auditable_id', $auditable_id)->where('auditable_type', 'Seafarer');
        });

        if ($request->has('current_page') && $request->has('per_page')) {
            $query = $query->limit($request->get('per_page'))->offset($request->get('per_page') * $request->get('current_page'));
        }
        $query->with('user');

        return Fractal::create()->collection($query->orderBy('created_at', 'desc')->get())
            ->transformWith(new AuditTransformer())
            ->toArray();
    }
}

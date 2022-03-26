<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\GeneralException;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Vessel;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Transformers\AuditTransformer;
use App\Transformers\VesselTransformer;
use App\Exports\VesselCertificatesExport;
use App\Http\Requests\StoreVesselRequest;
use App\Http\Requests\UpdateVesselRequest;
use App\Transformers\VesselIMOTransformer;
use App\Mail\CertificateExpiryNotification;
use App\Http\Requests\GenerateVesselIMORequest;
use App\Repositories\Contracts\VesselRepository;
use App\Mail\VesselCertificateExpiryNotification;

class VesselController extends BackendController
{
    /**
     * @var VesselRepository
     */
    protected $vessels;
    protected $warnings = [];

    protected $repo_name = 'vessels';

    /**
     * Create a new controller instance.
     *
     * @param VesselRepository $vessels
     */
    public function __construct(VesselRepository $vessels)
    {
        $this->vessels = $vessels;
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
        $query = $this->vessels->query()->with('pilot', 'port_of_registry', 'media', 'documents');

        if (! Gate::check('view vessels') && ! Gate::check('view vessel certificates') && ! Gate::check('view vessel forms')) {
            $query->where('piloted_by', auth()->user()->id);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['name', 'imo_number', 'flag', 'port_of_registry.name']);

        return $requestSearchQuery->result(new VesselTransformer());
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
    public function limited_search(Request $request)
    {
        $query = $this->vessels->query()->with('pilot', 'port_of_registry', 'media', 'documents');

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['name', 'imo_number', 'flag', 'port_of_registry.name']);

        return $requestSearchQuery->result(new VesselTransformer(true));
    }

    /**
     * @param Vessel $vessel
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Vessel $vessel)
    {
        $this->authorize('view', $vessel);

        return Fractal::create()->item($vessel, new VesselTransformer());
    }

    /**
     * @param StoreVesselRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreVesselRequest $request)
    {
        $this->authorize('create vessels');

        $this->vessels->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.vessels.created'));
    }

    /**
     * @param Vessel              $vessel
     * @param UpdateVesselRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Vessel $vessel, User $user, UpdateVesselRequest $request)
    {
        if ($user->can('edit own vessels') || $user->can('edit vessel certificates') || $user->can('edit vessel forms')) {
            $this->vessels->update($vessel, $request->all());

            return $this->redirectResponse($request, __('alerts.backend.vessels.updated'));
        }
        throw new GeneralException(__('exceptions.unauthorized'));
//        $this->authorize('edit own vessels' || 'edit vessel certificates' || 'edit doc audits');
//        dd('gg');
//
//        $this->vessels->update($vessel, $request->all());
//
//        return $this->redirectResponse($request, __('alerts.backend.vessels.updated'));
    }

    /**
     * @param Vessel  $vessel
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Vessel $vessel, Request $request)
    {
        $this->authorize('delete vessels');

        $this->vessels->destroy($vessel);

        return $this->redirectResponse($request, __('alerts.backend.vessels.deleted'));
    }

    /**
     * @param Vessel  $vessel
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function get_imo_reports(Vessel $vessel)
    {
        $imos = $vessel->media()->where('collection_name', 'IMO')->orderBy('id', 'desc')->get();

        return Fractal::create()->collection($imos, new VesselIMOTransformer());
    }

    /**
     * @param GenerateVesselIMORequest $request
     * @param Vessel                   $vessel
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process_imo_report(GenerateVesselIMORequest $request, Vessel $vessel)
    {
//        $this->authorize('generate vessel imo');

        $pdf = $this->vessels->process_imo_report($vessel, $request->all());

        if ($request->get('generate')) {
            return $this->redirectResponse($request, __('alerts.backend.vessels.imo_generated'));
        }

        return $pdf->stream();
    }

    public function getHistory(Request $request, $auditable_id)
    {
//        $Audit = DB::table('Audit')
//            ->orderBy('created_at', 'desc')
//            ->get();
        /** @var Vessel $vessel */
        $vessel = $this->{$this->repo_name}->query()->find($auditable_id);
        $Document = $vessel->documents->pluck('id')->toArray();

        $query = Audit::query();
        foreach ($Document as $item_id) {
            $query->orWhere(function ($q) use ($item_id) {
                return $q->where('auditable_id', $item_id)->where('auditable_type', 'Document');
            });
        }
        $query->orWhere(function ($q) use ($auditable_id) {
            return $q->where('auditable_id', $auditable_id)->where('auditable_type', 'Vessel');
        });

        if ($request->has('current_page') && $request->has('per_page')) {
            $query = $query->limit($request->get('per_page'))->offset($request->get('per_page') * $request->get('current_page'));
        }
        $query->with('user');

        return Fractal::create()->collection($query->orderBy('created_at', 'desc')->get())
            ->transformWith(new AuditTransformer())
            ->toArray();
    }

    public function expired_certificate()
    {
        $vessels = $this->vessels->query()->orderBy('id', 'desc')->get();
        $two_month = now()->addMonths(2);
        $current_month = now();
        $one_month = now()->addMonth(1);
        $warnings = [];
        $critical = [];
        $expired = [];
        $logo['path'] = asset('images/logo.png');

        foreach ($vessels as $vessel) {
            $certificates = $vessel->documents;
            $local_warnings = [];
            $local_critical = [];
            $local_expired = [];
            foreach ($certificates as $certificate) {
                if (! $certificate->document_expiry_date) {
                    continue;
                }
                // Expired after less than 1 Month
                if ($certificate->document_expiry_date <= $one_month->toDateString() && $certificate->document_expiry_date > $current_month->toDateString()) {
                    $expiry_date = new Carbon($certificate->document_expiry_date);
                    $now = Carbon::now();
                    $difference = $expiry_date->diff($now)->days < 1
                        ? 'today'
                        : $expiry_date->diffInDays($now);

                    if (! isset($critical[$certificate->documentable->name])) {
                        $critical[$certificate->documentable->name] = [];
                    }

                    array_push($critical[$certificate->documentable->name], ['certificate' => $certificate, 'differ' => $difference]);

                    array_push($local_critical, ['certificate' => $certificate, 'differ' => $difference]);
                } // Expired between 1 Month and 2 Months
                elseif ($certificate->document_expiry_date <= $two_month->toDateString() && $certificate->document_expiry_date > $current_month->toDateString()) {
                    $expiry_date = new Carbon($certificate->document_expiry_date);
                    $now = Carbon::now();
                    $difference = $expiry_date->diff($now)->days < 1
                        ? 'today'
                        : $expiry_date->diffInDays($now);

                    if (! isset($warnings[$certificate->documentable->name])) {
                        $warnings[$certificate->documentable->name] = [];
                    }

                    array_push($local_warnings, ['certificate' => $certificate, 'differ' => $difference]);

                    array_push($warnings[$certificate->documentable->name], ['certificate' => $certificate, 'differ' => $difference]);
                } // Already expired
                elseif ($certificate->document_expiry_date <= $current_month->toDateString()) {
                    if (! isset($expired[$certificate->documentable->name])) {
                        $expired[$certificate->documentable->name] = [];
                    }

                    array_push($local_expired, $certificate);

                    array_push($expired[$certificate->documentable->name], $certificate);
                }
            }

            // Send email for each vessel master about the expiry of his vessel's certificates

//            return view('emails.welcome', compact('warnings', 'critical','expired'));

            if ((\count($local_warnings) || \count($local_critical) || \count($local_expired)) && $vessel->pilot && $vessel->pilot->email) {
                $master_email = $vessel->pilot->email;
                $vessel_name = $certificate->documentable->name;
                Mail::to($master_email)
//                    ->cc(['a.alkhoudary@inaikiara.com.my', 'besher@inaikiara.com.my'])
                    ->send(new VesselCertificateExpiryNotification($local_warnings, $local_critical, $local_expired, $vessel_name, $logo));
            }
        }

        // Send a comprehensive about the expiry off all vessels' certificates to top management
        if (\count($warnings) || \count($critical) || \count($expired)) {
            Mail::to([
                'kamrul@inaikiara.com.my',
                'zaini@inaikiara.com.my',
                'sara@inaikiara.com.my',
                'rafi@inaikiara.com.my',
                'hazim@imwsb.com.my',
                'sabarudin@inaikiara.com.my',
                'hafiz@inaikiara.com.my',
                'jessie@inaikiara.com.my',
                'khairul@inaikiara.com.my',
                'athzree@inaikiara.com.my',
                'miorshahril@selatmelaka.com.my',
                'shahruladhwa@inaikiara.com.my',
                'postol5234@gmail.com',
            ])
            ->cc(['a.alkhoudary@inaikiara.com.my', 'besher@inaikiara.com.my', 'homam@iniakiara.com.my'])
            ->send(new CertificateExpiryNotification($warnings, $critical, $expired, $logo));
        }
    }

    /**
     * @param Vessel $vessel
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     *
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export_certificates(Vessel $vessel)
    {
        return Excel::download(new VesselCertificatesExport($vessel->documents->count(), $vessel), 'certificates.xlsx');
    }
}

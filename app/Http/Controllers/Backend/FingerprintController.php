<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FingerprintsImportHQ;
use App\Imports\FingerprintsImportPI;
use App\Transformers\FingerprintTransformer;
use App\Http\Requests\ImportFigerprintsRequest;
use App\Repositories\Contracts\FingerprintRepository;

class FingerprintController extends BackendController
{
    /**
     * @var FingerprintRepository
     */
    protected $fingerprints;

    /**
     * Create a new controller instance.
     *
     * @param FingerprintRepository $fingerprints
     */
    public function __construct(FingerprintRepository $fingerprints)
    {
        $this->fingerprints = $fingerprints;
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
        $query = $this->fingerprints->query()->with([]);
        $query->join('branches', 'branches.id', '=', 'fingerprints.branch_id');
        $query->select([DB::raw('branches.name as branch_name'), 'fingerprints.*']);

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new FingerprintTransformer());
    }

    /**
     * @param Request $request
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public function import(ImportFigerprintsRequest $request)
    {
        ini_set('memory_limit', '1024M');

        Excel::import('HQ' === $request->get('branch') ? new FingerprintsImportHQ() : new FingerprintsImportPI(), $request->file('file'));

        return $this->redirectResponse($request, __('alerts.backend.fingerprints.imported'));
    }
}

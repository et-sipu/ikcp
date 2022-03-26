<?php

namespace App\Http\Controllers\Backend;

use App\Models\DocAudit;
use App\Transformers\DocAuditTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreDocAuditRequest;
use App\Http\Requests\UpdateDocAuditRequest;
use App\Repositories\Contracts\DocAuditRepository;
use Spatie\Fractal\Fractal;

class DocAuditController extends BackendController
{

    /**
     * @var DocAuditRepository
     */
    protected $doc_audits;

    /**
     * Create a new controller instance.
     *
     * @param DocAuditRepository $doc_audits
     */
    public function __construct(DocAuditRepository $doc_audits)
    {
        $this->doc_audits = $doc_audits;
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
        $query = $this->doc_audits->query()->with([]);

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new DocAuditTransformer());
    }

    /**
     * @param DocAudit $doc_audit
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(DocAudit $doc_audit)
    {
        $this->authorize('view doc audits');

        return Fractal::create()->item($doc_audit, new DocAuditTransformer());
    }

    /**
     * @param StoreDocAuditRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDocAuditRequest $request)
    {
        $this->authorize('create doc audits');

        $this->doc_audits->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.doc_audits.created'));
    }

    /**
     * @param DocAudit $doc_audit
     * @param UpdateDocAuditRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DocAudit $doc_audit, UpdateDocAuditRequest $request)
    {
        $this->authorize('edit doc audits');

        $this->doc_audits->update($doc_audit, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.doc_audits.updated'));
    }

    /**
     * @param DocAudit $doc_audit
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(DocAudit $doc_audit, Request $request)
    {
        $this->authorize('delete doc audits');

        $this->doc_audits->destroy($doc_audit);

        return $this->redirectResponse($request, __('alerts.backend.doc_audits.deleted'));
    }
}
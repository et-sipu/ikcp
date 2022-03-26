<?php

namespace App\Http\Controllers\Backend;

use App\Models\DocTemplate;
use App\Transformers\DocTemplateTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreDocTemplateRequest;
use App\Http\Requests\UpdateDocTemplateRequest;
use App\Repositories\Contracts\DocTemplateRepository;
use Spatie\Fractal\Fractal;

class DocTemplateController extends BackendController
{

    /**
     * @var DocTemplateRepository
     */
    protected $doc_templates;

    /**
     * Create a new controller instance.
     *
     * @param DocTemplateRepository $doc_templates
     */
    public function __construct(DocTemplateRepository $doc_templates)
    {
        $this->doc_templates = $doc_templates;
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
        $query = $this->doc_templates->query()->with([]);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->template_type) && $extraSearch->template_type != '') {
            $query->where('template_type', $extraSearch->template_type);
        }

        if (isset($extraSearch->template_types) && is_array($extraSearch->template_types) && count($extraSearch->template_types) > 0) {
            $query->whereIn('template_type', $extraSearch->template_types);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new DocTemplateTransformer());
    }

    /**
     * @param DocTemplate $doc_template
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(DocTemplate $doc_template)
    {
        $this->authorize('view doc templates');

        return Fractal::create()->item($doc_template, new DocTemplateTransformer());
    }

    /**
     * @param StoreDocTemplateRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDocTemplateRequest $request)
    {
        $this->authorize('create doc templates');

        $this->doc_templates->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.doc_templates.created'));
    }

    /**
     * @param DocTemplate $doc_template
     * @param UpdateDocTemplateRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DocTemplate $doc_template, UpdateDocTemplateRequest $request)
    {
        $this->authorize('edit doc templates');

        $this->doc_templates->update($doc_template, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.doc_templates.updated'));
    }

    /**
     * @param DocTemplate $doc_template
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(DocTemplate $doc_template, Request $request)
    {
        $this->authorize('delete doc templates');

        $this->doc_templates->destroy($doc_template);

        return $this->redirectResponse($request, __('alerts.backend.doc_templates.deleted'));
    }
}
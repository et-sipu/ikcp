<?php

namespace App\Http\Controllers\Backend;

use App\Models\VesselForm;
use App\Transformers\VesselFormTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreVesselFormRequest;
use App\Http\Requests\UpdateVesselFormRequest;
use App\Repositories\Contracts\VesselFormRepository;
use Illuminate\Support\Facades\DB;
use Spatie\Fractal\Fractal;

class VesselFormController extends BackendController
{

    /**
     * @var VesselFormRepository
     */
    protected $vessel_forms;

    /**
     * Create a new controller instance.
     *
     * @param VesselFormRepository $vessel_forms
     */
    public function __construct(VesselFormRepository $vessel_forms)
    {
        $this->vessel_forms = $vessel_forms;
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
        $query = $this->vessel_forms->query()->with([]);

        $query->leftJoin('vessels', 'vessels.id', '=', 'vessel_forms.vessel_id');
        $query->leftJoin('doc_templates', 'doc_templates.id', '=', 'vessel_forms.doc_template_id');
        $query->select(['vessels.name as vessel_name',DB::raw('concat(\'(\',doc_templates.code,\') \',doc_templates.title) as template_name'), 'vessel_forms.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->templates) && \is_array($extraSearch->templates) && \count($extraSearch->templates) > 0) {
            $templates_ids = array_pluck($extraSearch->templates, 'id');

            $query->whereIn('doc_template_id', $templates_ids);
        }

        if (isset($extraSearch->template_type) && $extraSearch->template_type != '') {
            $tempalte_type = $extraSearch->template_type->id;
            $query->whereHas('doc_template', function ($q) use($tempalte_type){
                return $q->where('template_type', $tempalte_type);
            });
        }

        if(isset($extraSearch->vessel_id) && $extraSearch->vessel_id != 0) {
            $query->where('vessel_id', $extraSearch->vessel_id);
        }

        if (isset($extraSearch->date) && $extraSearch->date) {
            $dates = explode(' to ', $extraSearch->date);
            if (\count($dates) > 1) {
                $query->whereBetween('vessel_forms.created_at', $dates);
            } else {
                $query->where('vessel_forms.created_at', $dates);
            }
        }
        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new VesselFormTransformer());
    }

    /**
     * @param VesselForm $vessel_form
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VesselForm $vessel_form)
    {
         $this->authorize('view vessel forms');

        return Fractal::create()->item($vessel_form, new VesselFormTransformer());
    }

    /**
     * @param StoreVesselFormRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreVesselFormRequest $request)
    {
        // $this->authorize('create vessel forms');

        $this->vessel_forms->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.vessel_forms.created'));
    }

    /**
     * @param VesselForm $vessel_form
     * @param UpdateVesselFormRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(VesselForm $vessel_form, UpdateVesselFormRequest $request)
    {
        // $this->authorize('edit vessel forms');

        $this->vessel_forms->update($vessel_form, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.vessel_forms.updated'));
    }

    /**
     * @param VesselForm $vessel_form
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(VesselForm $vessel_form, Request $request)
    {
        // $this->authorize('delete vessel forms');

        $this->vessel_forms->destroy($vessel_form);

        return $this->redirectResponse($request, __('alerts.backend.vessel_forms.deleted'));
    }
}
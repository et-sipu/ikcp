<?php

namespace App\Http\Controllers\Backend;

use App\Models\Activity;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use App\Transformers\ActivityTransformer;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Repositories\Contracts\ActivityRepository;

class ActivityController extends BackendController
{
    /**
     * @var ActivityRepository
     */
    protected $activities;

    protected $repo_name = 'activities';

    /**
     * Create a new controller instance.
     *
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities)
    {
        $this->activities = $activities;
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

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['title']);

        return $requestSearchQuery->result(new ActivityTransformer());
    }

    /**
     * @param Activity $activity
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Activity $activity)
    {
        // $this->authorize('view activities');

        return Fractal::create()->item($activity, new ActivityTransformer());
    }

    /**
     * @param StoreActivityRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreActivityRequest $request)
    {
        $this->authorize('create activities');

        $this->activities->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.activities.created'));
    }

    /**
     * @param Activity              $activity
     * @param UpdateActivityRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Activity $activity, UpdateActivityRequest $request)
    {
        $this->authorize('edit activities');

        $this->activities->update($activity, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.activities.updated'));
    }

    /**
     * @param Activity $activity
     * @param Request  $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Activity $activity, Request $request)
    {
        $this->authorize('delete activities');

        $this->activities->destroy($activity);

        return $this->redirectResponse($request, __('alerts.backend.activities.deleted'));
    }

    /**
     * @param Request $request
     * @param $commentable_id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $commentable_id)
    {
        $activity = $this->activities->query()->find($commentable_id);

        $this->authorize('add_comment', $activity);

        if ($request->get('done')) {
            $this->activities->changeStatus($activity, 'achieve');
        }

        return parent::addComment($request, $commentable_id);
    }

    /**
     * @param Request  $request
     * @param Activity $activity
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Activity $activity)
    {
        // $this->authorize('approve activities');
        $this->activities->approve($activity, $request->get('approved_cost'));

        return $this->redirectResponse($request, __('alerts.backend.activities.status_changed'));
    }

    /**
     * @param Request  $request
     * @param Activity $activity
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, Activity $activity, $status)
    {
        $this->authorize('edit tasks');

        $this->activities->changeStatus($activity, $status);

        return $this->redirectResponse($request, __('alerts.backend.activities.status_changed'));
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print(Request $request)
    {
        $this->authorize('print activities');

        $activities = $this->extractQuery($request)->get();

        return view('templates.activities_report', compact('activities'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function extractQuery(Request $request)
    {
        $query = $this->activities->query()->with(['by_department', 'from_department', 'transitions']);
        $query->join('departments as dept_by', 'dept_by.id', '=', 'activities.action_by');
        $query->join('departments as dept_from', 'dept_from.id', '=', 'activities.action_from');
        $query->select([DB::raw('dept_by.name as action_by'), DB::raw('dept_from.name as action_from'), 'activities.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->action_by) && \is_array($extraSearch->action_by) && \count($extraSearch->action_by) > 0) {
            $action_by_depts = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->action_by);
            $query = $query->ByDepartment($action_by_depts);
        }
        if (isset($extraSearch->action_from) && \is_array($extraSearch->action_from) && \count($extraSearch->action_from) > 0) {
            $action_from_depts = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->action_from);
            $query = $query->FromDepartment($action_from_depts);
        }
        if (isset($extraSearch->statuses)) {
            $query = $query->whereIn('status', $extraSearch->statuses);
        }

        if (! Gate::check('view activities')) {
            $query->ForUser(auth()->user()->id);
        }

        return $query;
    }
}

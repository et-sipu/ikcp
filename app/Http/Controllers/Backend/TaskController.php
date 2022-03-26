<?php

namespace App\Http\Controllers\Backend;

use App\Models\Task;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Transformers\TaskTransformer;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\Contracts\TaskRepository;

class TaskController extends BackendController
{
    /**
     * @var TaskRepository
     */
    protected $tasks;

    protected $repo_name = 'tasks';

    /**
     * Create a new controller instance.
     *
     * @param TaskRepository $tasks
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->tasks = $tasks;
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

        return $requestSearchQuery->result(new TaskTransformer());
    }

    /**
     * @param Task $task
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Task $task)
    {
        // $this->authorize('view tasks');

        return Fractal::create()->item($task, new TaskTransformer());
    }

    /**
     * @param StoreTaskRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create tasks');

        $this->tasks->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.tasks.created'));
    }

    /**
     * @param Task              $task
     * @param UpdateTaskRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
//    public function update(Task $task, UpdateTaskRequest $request)
//    {
//        // $this->authorize('edit tasks');
//
//        $this->tasks->update($task, $request->all());
//
//        return $this->redirectResponse($request, __('alerts.backend.tasks.updated'));
//    }

    /**
     * @param Task    $task
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Task $task, Request $request)
    {
        $this->authorize('delete tasks');

        $this->tasks->destroy($task);

        return $this->redirectResponse($request, __('alerts.backend.tasks.deleted'));
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
        $task = $this->tasks->query()->find($commentable_id);

        $this->authorize('add_comment', $task);

        if ($request->get('done')) {
            $this->tasks->changeStatus($task, 'achieve');
        }

        return parent::addComment($request, $commentable_id);
    }

    /**
     * @param Request $request
     * @param Task    $task
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, Task $task, $status)
    {
        $this->authorize('edit tasks');

        $this->tasks->changeStatus($task, $status);

        return $this->redirectResponse($request, __('alerts.backend.tasks.status_changed'));
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
        $this->authorize('print tasks');

        $tasks = $this->extractQuery($request)->get();

        return view('templates.tasks_report', compact('tasks'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function extractQuery(Request $request)
    {
        $query = $this->tasks->query()->with(['assigned_department', 'transitions']);
        $query->join('departments', 'departments.id', '=', 'tasks.assigned_department_id');
        $query->select([DB::raw('departments.name as department_name'), 'tasks.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->assigned_department) && \is_array($extraSearch->assigned_department) && \count($extraSearch->assigned_department) > 0) {
            $assigned_department = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->assigned_department);
            $query = $query->ForDepartment($assigned_department);
        }
        if (isset($extraSearch->statuses)) {
            $query = $query->whereIn('status', $extraSearch->statuses);
        }
        if (isset($extraSearch->id) && ! empty($extraSearch->id)) {
            $query = $query->whereIn('tasks.id', [$extraSearch->id]);
        }

        if (! Gate::check('view tasks')) {
            $query->ForUser(auth()->user()->id);
        }

        return $query;
    }
}

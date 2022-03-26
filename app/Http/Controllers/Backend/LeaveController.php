<?php

namespace App\Http\Controllers\Backend;

use App\Models\Leave;
use App\Models\Employee;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Transformers\LeaveTransformer;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\LeaveRepository;

class LeaveController extends BackendController
{
    /**
     * @var LeaveRepository
     */
    protected $leaves;

    /**
     * @var string
     */
    protected $repo_name = 'leaves';

    /**
     * Create a new controller instance.
     *
     * @param LeaveRepository $leaves
     */
    public function __construct(LeaveRepository $leaves)
    {
        $this->leaves = $leaves;
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
        $query = $this->leaves->query()->with([]);
        $query->JoinWithDepartment();
        if (! Gate::check('view leaves')) {
//            $query->MyRequisitions(auth()->user()->id);
            $query->MyRecords(auth()->user()->employee->id, auth()->user()->id);
        }
        $query->select([DB::raw('users.name as requester_name'), DB::raw('departments.name as department_name'), 'leaves.*']);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->departments) && \is_array($extraSearch->departments) && \count($extraSearch->departments) > 0) {
            $department_ids = array_pluck($extraSearch->departments, 'id');
            $query->whereHas('employee', function (Builder $q) use ($department_ids) {
                return $q->InOneOfDepartments($department_ids);
            });
        }

        if (isset($extraSearch->branches) && \is_array($extraSearch->branches) && \count($extraSearch->branches) > 0) {
            $branch_ids = array_pluck($extraSearch->branches, 'id');
            $query->whereHas('employee', function (Builder $q) use ($branch_ids) {
                return $q->InOneOfBranches($branch_ids);
            });
        }

        if (isset($extraSearch->employees) && \is_array($extraSearch->employees) && \count($extraSearch->employees) > 0) {
            $requester_ids = array_pluck($extraSearch->employees, 'id');
            $query->whereIn('leaves.employee_id', $requester_ids);
        }

        if (isset($extraSearch->date) && $extraSearch->date) {
            $dates = explode(' to ', $extraSearch->date);
            if (\count($dates) > 1) {
                $query->where(function (Builder $q) use ($dates) {
                    $q->whereBetween('start_date', $dates)->orWhereBetween('end_date', $dates);
                });
            } else {
                $query->where('start_date', '<=', $dates)->where('end_date', '>=', $dates);
            }
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new LeaveTransformer());
    }

    /**
     * @param Leave $leave
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Leave $leave)
    {
        $this->authorize('view', $leave);

        return Fractal::create()->item($leave, new LeaveTransformer());
    }

    /**
     * @param StoreLeaveRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreLeaveRequest $request)
    {
        $this->authorize('create leaves');

        $this->leaves->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.leaves.created'));
    }

    /**
     * @param Leave              $leave
     * @param UpdateLeaveRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Leave $leave, UpdateLeaveRequest $request)
    {
        $this->authorize('edit', $leave);

        $this->leaves->update($leave, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.leaves.updated'));
    }

    /**
     * @param Leave   $leave
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Leave $leave, Request $request)
    {
        $this->authorize('delete', $leave);

        $this->leaves->destroy($leave);

        return $this->redirectResponse($request, __('alerts.backend.leaves.deleted'));
    }

    /**
     * @param Request $request
     * @param Leave   $leave
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, Leave $leave, $status)
    {
        $this->authorize('pass', [$leave, $status]);

        $this->leaves->changeStatus($leave, $status);

        return $this->redirectResponse($request, __('alerts.backend.leaves.status_changed'));
    }

    /**
     * @param Employee|null $employee
     * @return mixed
     */
    public function leaveEntitlements(Employee $employee = null)
    {
        return $this->leaves->employee_leave_entitlements($employee);
    }
}

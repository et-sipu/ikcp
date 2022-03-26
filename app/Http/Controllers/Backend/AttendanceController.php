<?php

namespace App\Http\Controllers\Backend;

use App\Models\Employee;
use App\Models\Attendance;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceRepresentative;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\RepresentTransformer;
use App\Transformers\AttendanceTransformer;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Requests\StoreAttendanceRepresentative;
use App\Repositories\Contracts\AttendanceRepository;
use App\Http\Requests\UpdateAttendanceRepresentative;
use App\Repositories\Contracts\AttendanceRepresentativeRepository;

class AttendanceController extends BackendController
{
    /**
     * @var AttendanceRepository
     */
    protected $attendances;
    /**
     * @var AttendanceRepresentativeRepository
     */
    private $represent;

    /**
     * Create a new controller instance.
     *
     * @param AttendanceRepository $attendances
     */
    public function __construct(AttendanceRepository $attendances, AttendanceRepresentativeRepository $represent)
    {
        $this->attendances = $attendances;
        $this->represent = $represent;
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

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['employee.name-surname']);

        $data = $requestSearchQuery->result(new AttendanceTransformer());

        $data['summery'] = $this->get_query_summary($query);

        return $data;
    }

    /**
     * @param Attendance $attendance
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Attendance $attendance)
    {
        // $this->authorize('view attendances');

        return Fractal::create()->item($attendance, new AttendanceTransformer());
    }

    /**
     * @param StoreAttendanceRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreAttendanceRequest $request)
    {
        // $this->authorize('create attendances');

        $this->attendances->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.attendances.created'));
    }

    /**
     * @param Attendance              $attendance
     * @param UpdateAttendanceRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Attendance $attendance, UpdateAttendanceRequest $request)
    {
        // $this->authorize('edit attendances');

        $this->attendances->update($attendance, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.attendances.updated'));
    }

    /**
     * @param Attendance $attendance
     * @param Request    $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Attendance $attendance, Request $request)
    {
        // $this->authorize('delete attendances');

        $this->attendances->destroy($attendance);

        return $this->redirectResponse($request, __('alerts.backend.attendances.deleted'));
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function export(Request $request)
    {
        $this->authorize('export attendances');

        $query = $this->extractQuery($request);
        $query->orderBy('attendance_date');
        $attendance_data = $query->get();
        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

        $dates = [];
        $employee = null;
        if (isset($extraSearch->date) && $extraSearch->date) {
            $dates = explode(' to ', $extraSearch->date);
        }
        if (isset($extraSearch->employees) && $extraSearch->employees) {
            $employee = Employee::find($extraSearch->employees[0]->id);
        }
        $summery = $this->get_query_summary($query);

        return view('templates.attendance_export', compact('attendance_data', 'dates', 'employee', 'summery'));
    }

    public function get_query_summary($query)
    {
        $summary = 0;
        $query->each(function ($employee) use (&$summary) {
            $summary += $employee->hr_review;
        });

        return $summary;
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function get_dept_movement()
    {
        $this->authorize('create attendances');

        return $this->attendances->get_dept_movement();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function save_dept_movement(Request $request)
    {
        $this->authorize('create attendances');

        $this->attendances->save_dept_movement($request->input());

        return $this->redirectResponse($request, __('alerts.backend.attendances.saved'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function representative_search(Request $request)
    {
        $query = AttendanceRepresentative::query();

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new RepresentTransformer());
    }

    /**
     * @param StoreAttendanceRepresentative $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store_represent(StoreAttendanceRepresentative $request)
    {
        $this->represent->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.representative.created'));
    }

    /**
     * @param AttendanceRepresentative $representative
     * @param UpdateAttendanceRepresentative $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update_represent(AttendanceRepresentative $representative, UpdateAttendanceRepresentative $request)
    {
        $this->represent->update($representative, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.representative.updated'));
    }

    /**
     * @param AttendanceRepresentative $representative
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete_represent(AttendanceRepresentative $representative, Request $request)
    {
        // $this->authorize('delete attendances');

        $this->represent->destroy($representative);

        return $this->redirectResponse($request, __('alerts.backend.representative.deleted'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function extractQuery(Request $request)
    {
        $query = $this->attendances->query()->with(['employee.jobInfo']);
        $query->join('employees', 'employees.id', '=', 'attendances.employee_id');
        $query->select([DB::raw('employees.name as employee_name'), 'attendances.*']);

        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

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

        if (isset($extraSearch->date) && $extraSearch->date) {
            $dates = explode(' to ', $extraSearch->date);
            if (\count($dates) > 1) {
                $query->whereBetween('attendance_date', $dates);
            } else {
                $query->where('attendance_date', $dates);
            }
        }

        if (isset($extraSearch->employees) && \is_array($extraSearch->employees) && \count($extraSearch->employees) > 0) {
            $requester_ids = array_pluck($extraSearch->employees, 'id');
            $query->whereIn('employee_id', $requester_ids);
        }

        if (isset($extraSearch->dept_movements) && \is_array($extraSearch->dept_movements) && \count($extraSearch->dept_movements) > 0) {
            $movements_ids = array_pluck($extraSearch->dept_movements, 'id');
            $query->whereIn('dept_movement', $movements_ids);
        }

        return $query;
    }
}

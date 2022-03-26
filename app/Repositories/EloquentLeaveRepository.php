<?php

namespace App\Repositories;

use Exception;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\LeaveTypeEntitlement;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\LeaveRepository;
use App\Repositories\Traits\TransmittableTrait;
use App\Repositories\Traits\SyncAttachmentsTrait;

/**
 * Class EloquentLeaveRepository.
 */
class EloquentLeaveRepository extends EloquentBaseRepository implements LeaveRepository
{
    use SyncAttachmentsTrait;
    use TransmittableTrait;

    /**
     * EloquentLeaveRepository constructor.
     *
     * @param Leave $leave
     */
    public function __construct(Leave $leave)
    {
        parent::__construct($leave);
    }

    /**
     * @param $employee_id
     * @param $start_date
     * @param $end_date
     *
     * @return Leave
     */
    public function find($employee_id, $start_date, $end_date)
    {
        /** @var Leave $leave */
        return $this->query()
            ->where('employee_id', $employee_id)
            ->where('start_date', '<=', $end_date)
            ->where('end_date', '>=', $start_date)
            ->where('status', '<>', 'rejected')
            ->first();
    }

    /**
     * @param Employee $employee
     * @param $leave_type
     *
     * @return int|mixed
     */
    public function leave_entitlements_count(Employee $employee, $leave_type)
    {
        $leave_type_entitlement = LeaveTypeEntitlement::where(function (Builder $q) use ($employee) {
            return $q->where('level_id', $employee->employment_level->id)->orWhereNull(level_id);
        })
            ->where('leave_type', $leave_type)
            ->where('min_years', '<=', $employee->service_years)
            ->where('max_years', '>=', $employee->service_years)
            ->first();

        if (! $leave_type_entitlement) {
            return 1000;
        }

        return $leave_type_entitlement->entitlements;
    }

    /**
     * @param Employee $employee
     * @param $leave_type
     *
     * @return int|mixed
     */
    public function leave_entitlements_count_prorated(Employee $employee, $leave_type)
    {
        $leave_type_entitlement = LeaveTypeEntitlement::where(function (Builder $q) use ($employee) {
            return $q->where('level_id', $employee->employment_level->id)->orWhereNull('level_id');
        })
            ->where('leave_type', $leave_type)
            ->where('min_years', '<=', $employee->service_years)
            ->where('max_years', '>=', $employee->service_years)
            ->first();

        if (! $leave_type_entitlement) {
            return 1000;
        }

//        $service_months_in_this_year = Carbon::createFromFormat('Y-m-d', $employee->date_of_join)->format('Y') == Carbon::today()->format('Y')
//        ? Carbon::createFromFormat('Y-m-d', $employee->date_of_join)->diffInMonths(Carbon::today()) +1
//        : Carbon::today()->format('n');

        $join_date = $employee->date_of_join ? Carbon::createFromFormat('Y-m-d', $employee->date_of_join)->startOfDay() : Carbon::today()->startOfYear();
        $service_months_in_this_year = ($join_date->diffInMonths(Carbon::today()) + 1) % 12;

        return $leave_type_entitlement->prorated_entitlement ? round(($leave_type_entitlement->entitlements / 12) * $service_months_in_this_year, 2) : $leave_type_entitlement->entitlements;
    }

    /**
     * @param Employee $employee
     * @param $leave_type
     * @param $days_count
     * @param $except
     *
     * @return bool
     */
    public function check_eligibility(Employee $employee, $leave_type, $days_count, $except = [])
    {
//        return $this->leave_entitlements_count($employee, $leave_type) - ($this->leave_entitlements_count() +  $days_count) >= 0;
        return $this->leave_entitlements_count_prorated($employee, $leave_type) - ($this->leaves_taken($employee, $leave_type, $except) + $days_count) >= 0;
    }

    /**
     * @param Employee $employee
     * @param $leave_type
     * @param array $except
     *
     * @return int
     */
    public function leaves_taken(Employee $employee, $leave_type, $except = [])
    {
        $start_of_latest_contract = $employee->latest_contracting_date;
        $leaves = $this->query()
            ->where('employee_id', $employee->id)
//            ->whereYear('start_date', date("Y"))
            ->where('start_date', '>=', $start_of_latest_contract->format('Y-m-d'))
            ->where('status', '<>', 'rejected')
            ->where('leave_type', '=', $leave_type);

        if (\count($except) > 0) {
            $leaves->whereNotIn('id', $except);
        }

        $leaves = $leaves->get();

        $leaves_taken = 0;

        $leaves->each(function ($leave) use (&$leaves_taken) {
            $leaves_taken += $leave->days_count;
        });

        return $leaves_taken;
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Leave
     */
    public function store(array $input)
    {
        /** @var Leave $leave */
        $input['leave_type'] = $input['leave_type']['id'];
        $leave = $this->make(array_except($input, []));
        $leave->requester_id = auth()->user()->id;
        $leave->employee_id = auth()->user()->employee->id;

        if ($this->find($leave->employee_id, $leave->start_date, $leave->end_date)) {
            throw new GeneralException(__('exceptions.backend.leaves.already_exist'));
        }

        if ('on_probation' === $leave->employee->jobInfo->employment_status && 'AL' === $input['leave_type']) {
            throw new GeneralException(__('exceptions.backend.leaves.employee_on_probation'));
        }

        if (! $this->check_eligibility($leave->employee, $leave->leave_type, $leave->days_count)) {
            throw new GeneralException(__('exceptions.backend.leaves.insufficient_entitlement'));
        }

        DB::transaction(function () use ($leave, $input) {
            if (! $leave->save()) {
                throw new GeneralException(__('exceptions.backend.leaves.create'));
            }

            $this->sync_attachments($leave, $input['attachments'] ? $input['attachments'] : []);

            return true;
        });

        return $leave;
    }

    /**
     * @param Leave $leave
     * @param array $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Leave
     */
    public function update(Leave $leave, array $input)
    {
        $input['leave_type'] = $input['leave_type']['id'];
        if (($existingLeave = $this->find(auth()->user()->employee->id, $input['start_date'], $input['end_date']))
          && $existingLeave->id !== $leave->id
        ) {
            throw new GeneralException(__('exceptions.backend.leaves.already_exist'));
        }

        $leave->fill(array_except($input, []));

        if ('on_probation' === $leave->employee->jobInfo->employment_status && 'AL' === $input['leave_type']) {
            throw new GeneralException(__('exceptions.backend.leaves.employee_on_probation'));
        }

        if (! $this->check_eligibility($leave->employee, $leave->leave_type, $leave->days_count, [$leave->id])) {
            throw new GeneralException(__('exceptions.backend.leaves.insufficient_entitlement'));
        }

        DB::transaction(function () use ($leave, $input) {
            if (! $leave->save()) {
                throw new GeneralException(__('exceptions.backend.leaves.update'));
            }

            $this->sync_attachments($leave, isset($input['attachments']) && $input['attachments'] ? $input['attachments'] : []);

            return true;
        });

        return $leave;
    }

    /**
     * @param Leave $leave
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Leave $leave)
    {
        if (! $leave->delete()) {
            throw new GeneralException(__('exceptions.backend.leaves.delete'));
        }

        return true;
    }

    /**
     * @param Employee|null $employee
     * @return array|mixed
     */
    public function employee_leave_entitlements(Employee $employee = null)
    {
        if (! $employee) {
            $employee = auth()->user()->employee;
        }
        $employee_leave_entitlement = [];

//        $leave_type_entitlement = LeaveTypeEntitlement::where(function (Builder $q) use ($employee) {
//            return $q->where('level_id', $employee->employment_level->id)->orWhereNull('level_id');
//        })
//            ->where('min_years', '<=', $employee->service_years)
//            ->where('max_years', '>=', $employee->service_years)
//            ->select(['leave_type', 'entitlements'])
//            ->get()->each(function ($type) use (&$employee_leave_entitlement, $employee) {
//                $employee_leave_entitlement[$type->leave_type] = $this->leave_entitlements_count_prorated($employee, $type->leave_type);
//            });

        $leaves_taken = $this->query()
            ->where('employee_id', $employee->id)
            ->whereYear('start_date', date('Y'))
            ->where('status', '<>', 'rejected')
            ->get();

        $leaves = [];
        $leaves_taken->each(function ($leave) use (&$leaves) {
            $leaves[$leave->leave_type] = isset($leaves[$leave->leave_type]) ? $leaves[$leave->leave_type] + $leave->days_count : $leave->days_count;
        });

//        $leaves['SL'] = 12;
//        $leaves['RL'] = 22;
        $employee_leave_entitlements = [];

        foreach (array_unique(array_merge(array_keys($employee_leave_entitlement), array_keys($leaves))) as $leave_type) {
            $elements = [];
            $elements['leave_type'] = $leave_type;
            $elements['entitle'] = isset($employee_leave_entitlement[$leave_type]) ? $employee_leave_entitlement[$leave_type] : 0;
            $elements['taken'] = isset($leaves[$leave_type]) ? $leaves[$leave_type] : 0;
            array_push($employee_leave_entitlements, $elements);
        }

        return $employee_leave_entitlements;
    }

    public function make_transformation(Leave $leave, &$transition)
    {
        if ('hod_approved' === $transition && $this->leaves_in_a_row($leave, 3)) {
            $transition = 'hod_approved_long_leave';
        }

        return true;
    }

    /**
     * @param Leave $leave
     * @param $count
     *
     * @return bool
     */
    public function leaves_in_a_row(Leave $leave, $count)
    {
        if ($leave->days_count > $count) {
            return true;
        }

        $days_before = $this->leaves_before_in_a_row($leave);
        if ($leave->days_count + $days_before > $count) {
            return true;
        }

        return $leave->days_count + $days_before + $this->leaves_after_in_a_row($leave) > $count;
    }

    /**
     * @param Leave $leave
     *
     * @return int|mixed
     */
    public function leaves_before_in_a_row(Leave $leave)
    {
        $previous_leave = $this->query()->where('employee_id', $leave->employee_id)
            ->where('end_date', '<', $leave->start_date)->where('status', '!=', 'rejected')
            ->orderByDesc('end_date')->first();
        if (! $previous_leave) {
            return 0;
        }

        if (days_count($previous_leave->end_date, $leave->start_date) <= 2) {
            return $previous_leave->days_count + $this->leaves_before_in_a_row($previous_leave);
        }

        return 0;
    }

    /**
     * @param Leave $leave
     *
     * @return int|mixed
     */
    public function leaves_after_in_a_row(Leave $leave)
    {
        $next_leave = $this->query()->where('employee_id', $leave->employee_id)
            ->where('start_date', '>', $leave->end_date)->where('status', '!=', 'rejected')
            ->orderByDesc('start_date')->first();
        if (! $next_leave) {
            return 0;
        }
        if (days_count($leave->end_date, $next_leave->start_date) <= 2) {
            return $next_leave->days_count + $this->leaves_before_in_a_row($next_leave);
        }

        return 0;
    }
}

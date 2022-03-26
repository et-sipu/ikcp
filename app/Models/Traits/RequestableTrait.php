<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait RequestableTrait
{
    // Relations
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Scopes
    public function scopeMyRequisitions(Builder $query, $user_id)
    {
        return $query->where(function (Builder $query) use ($user_id) {
            $query->where('requester_id', $user_id);
            $query->orWhereHas('requester', function (Builder $q) use ($user_id) {
                return $q->whereHas('department', function (Builder $q) use ($user_id) {
                    return $q->where('hod_id', $user_id);
                });
            });
        });
    }

    public function scopeMyRecords(Builder $query, $employee_id, $user_id)
    {
        return $query->where(function (Builder $query) use ($employee_id, $user_id) {
            $query->where($this->getTable().'.employee_id', $employee_id);
            $query->orWhereHas('employee', function (Builder $q) use ($user_id) {
                return $q->whereHas('department', function (Builder $q) use ($user_id) {
                    return $q->where('hod_id', $user_id);
                });
            });
            $query->orWhereHas('employee', function (Builder $q) use ($employee_id) {
                return $q->whereHas('report_to', function (Builder $q) use ($employee_id) {
//                    return $q->where('report_to.id', $employee_id);
//                    return $q->where('employee_job_infos.report_to_id', $employee_id);
                    return $q->where('employee_job_infos.report_to_id', $employee_id);
                });
            });
        });
    }

    /**
     * @param Builder $query
     *
     * @throws \ReflectionException
     *
     * @return Builder
     */
    public function scopeJoinWithRequester(Builder $query)
    {
        $query->join('users', 'users.id', '=', $this->get_requestable_name().'.requester_id');
        $query->join('employees', 'employees.user_id', '=', 'users.id');

        return $query;
    }

    /**
     * @param Builder $query
     *
     * @throws \ReflectionException
     *
     * @return Builder
     */
    public function scopeJoinWithDepartment(Builder $query)
    {
        $query->JoinWithRequester();
        $query->join('employee_job_infos', 'employee_job_infos.employee_id', '=', 'employees.id');
        $query->join('departments', 'departments.id', '=', 'employee_job_infos.department_id');

        return $query;
    }

    /**
     * @param Builder $query
     * @param $department_ids
     *
     * @return mixed
     */
    public function scopeInOneOfDepartments(Builder $query, $department_ids)
    {
        return $query->whereHas('requester', function (Builder $q) use ($department_ids) {
            $q->whereHas('employee', function (Builder $q) use ($department_ids) {
                $q->whereHas('department', function (Builder $q) use ($department_ids) {
                    return $q->whereIn('department_id', $department_ids);
                });
            });
        });
    }

    // Methods

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    public function get_requestable_name()
    {
        return Str::plural(Str::snake($this->get_requestable_type()));
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    public function get_requestable_type()
    {
        $reflect = new \ReflectionClass($this);

        return $reflect->getShortName();
    }
}

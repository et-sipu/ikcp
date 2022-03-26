<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Collection;

class Attendance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'dept_movement',
        'logged_in_time',
        'logged_out_time',
        'hr_review',
        'ey_review',
        'remarks',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true; //Gate::check('edit {{modelNamePluralLowerCase}}');
    }

    public function getCanDeleteAttribute()
    {
        return true; //Gate::check('delete {{modelNamePluralLowerCase}}');
    }

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes

    // Methods
    public function fingerprints()
    {
//        if($this->employee->jobInfo && is_array($this->employee->jobInfo->finger_print_ids) && count($this->employee->jobInfo->finger_print_ids) > 0)
        if ($this->employee->fingerprints->count() > 0) {
            $attendance_date = $this->attendance_date;
//            dd($this->employee->fingerprints);

            return $this->employee->fingerprints->filter(function ($item) use ($attendance_date) {
                return false !== mb_stristr($item->clocking, $attendance_date);
            });
        }

        return new Collection();
    }
}

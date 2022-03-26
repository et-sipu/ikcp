<?php

namespace App\Models;

use App\Models\Traits\WorkflowTrait;
use Illuminate\Support\Facades\Gate;
use App\Models\Contracts\Commentable;
use App\Models\Contracts\Workflowable;
use App\Models\Traits\CommentableTrait;
use App\Models\Traits\RequestableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Traits\GetAttachmentsTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Leave extends Model implements HasMedia, Auditable, Workflowable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasMediaTrait;
    use WorkflowTrait;
    use CommentableTrait;
    use RequestableTrait;
    use GetAttachmentsTrait;

    protected $fillable = [
        'requester_id',
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'period',
        'reason',
        'status',
    ];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanViewAttribute()
    {
        return Gate::check('view', $this);
    }

    public function getCanPassAttribute()
    {
        return Gate::check('pass', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete', $this);
    }

    public function getDaysCountAttribute()
    {
        return $this->period && 'F' !== $this->period ? 0.5 : days_count($this->start_date, $this->end_date);

//            Carbon::createFromFormat('Y-m-d', $this->end_date)->addDay(1)->diffInDaysFiltered(function(Carbon $date) {
//                return !$date->isWeekend();
//            },Carbon::createFromFormat('Y-m-d', $this->start_date));
    }

    // Relations

    // Scopes

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/HR/leaves/'.$this->id.'/edit';
    }

    public function get_editted_url()
    {
        return $this->get_edit_url();
    }
}

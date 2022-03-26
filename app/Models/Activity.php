<?php

namespace App\Models;

use App\Models\Traits\WorkflowTrait;
use Illuminate\Support\Facades\Gate;
use App\Models\Contracts\Commentable;
use App\Models\Contracts\Workflowable;
use App\Models\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;

class Activity extends Model implements Auditable, Commentable, Workflowable
{
    use \OwenIt\Auditing\Auditable;
    use CommentableTrait;
    use WorkflowTrait;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'cost',
        'action_by',
        'action_from',
        'status',
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

    public function getCanMarkAsDoneAttribute()
    {
        return Gate::check('mark_as_done', $this);
    }

    // Relations
    public function from_department()
    {
        return $this->belongsTo(Department::class, 'action_from');
    }

    public function by_department()
    {
        return $this->belongsTo(Department::class, 'action_by');
    }

    // Scopes
    public function scopeForUser(Builder $query, $user_id)
    {
        return $query->where(function (Builder $query) use ($user_id) {
            $query->whereHas('by_department', function (Builder $q) use ($user_id) {
                return $q->where('hod_id', $user_id);
            });
            $query->orWhereHas('from_department', function (Builder $q) use ($user_id) {
                return $q->where('hod_id', $user_id);
            });
        });
    }

    public function scopeFromDepartment(Builder $query, $action_from_depts = [])
    {
        return $query->whereIn('action_from', $action_from_depts);
    }

    public function scopeByDepartment(Builder $query, $action_by_depts)
    {
        return $query->whereIn('action_by', $action_by_depts);
    }

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return 'Work/activities';
    }

    public function get_editted_url()
    {
        return config('app.url').'Work/activities';
    }

    public function get_title()
    {
        return $this->title;
    }
}

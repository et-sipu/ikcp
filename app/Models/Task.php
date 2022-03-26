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
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model implements Auditable, Workflowable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use CommentableTrait;
    use WorkflowTrait;

    public static $initial_state = 'pending';

    protected $fillable = [
        'title',
        'description',
        'assigned_department_id',
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
    public function assigned_department()
    {
        return $this->belongsTo(Department::class, 'assigned_department_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }

    public function assignee()
    {
        return $this->assigned_department->hod;
    }

    // Scopes
    public function scopeForUser(Builder $query, $user_id)
    {
        return $query->whereHas('assigned_department', function (Builder $q) use ($user_id) {
            return $q->where('hod_id', $user_id);
        });
    }

    public function scopeForDepartment(Builder $query, $departments = [])
    {
        return $query->whereIn('assigned_department_id', $departments);
    }

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/Work/tasks/'.$this->id.'/edit';
    }

    public function get_editted_url()
    {
        return config('app.url').'Work/tasks';
    }

    public function get_url()
    {
        return config('app.url').'/Work/tasks?id='.$this->id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function getStackHolders()
    {
//        $users = [];
//        $users[$this->assigner->email] = $this->assigner;
//        $users[$this->assignee()->email] = $this->assignee();
//        return $users;

        return [$this->assigner, $this->assignee()];
    }
}

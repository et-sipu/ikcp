<?php

namespace App\Models;

use App\Models\Contracts\Commentable;
use App\Models\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class Equipment extends Model implements Auditable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use CommentableTrait;

    protected $fillable = [
		'name',
		'brand',
		'model',
		'location',
		'serial_num',
		'status',
		'vessel_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete', $this);
    }

    // Relations

    // Scopes

    // Methods
    public function get_editted_url()
    {
        return $this->get_edit_url();
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

}

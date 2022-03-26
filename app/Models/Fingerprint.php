<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    protected $fillable = [
        'staff_id',
        'staff_name',
        'clocking',
        'terminal',
        'branch_id',
    ];

    public $timestamps = false;

    protected $dates = [
        'clocking',
    ];

    // Properties
    public function getCanEditAttribute()
    {
        return true; //Gate::check('edit fingerprints');
    }

    public function getCanDeleteAttribute()
    {
        return true; //Gate::check('delete fingerprints');
    }

    // Relations
    public function thumb()
    {
        return $this->belongsTo(Thumb::class, 'staff_id', 'staff_id');
    }

    // Scopes

    // Methods
}

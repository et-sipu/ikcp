<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    protected $fillable = [
        'staff_id',
        'employee_id',
    ];

    public $timestamps = false;

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function fingerprints()
    {
        return $this->hasMany(Fingerprint::class, 'staff_id', 'staff_id');
    }

    // Scopes

    // Methods
}

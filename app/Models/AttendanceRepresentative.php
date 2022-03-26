<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRepresentative extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'departments',
    ];

    protected $casts = [
        'departments' => 'array',
    ];

    public function assigned_department()
    {
        return Department::whereIn('id', $this->departments)->get();
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigned_branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

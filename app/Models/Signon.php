<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signon extends Model
{
    protected $fillable = [
      'sign_date',
      'sign_type',
      'status',
    ];

    // Relations
    public function contract()
    {
        return $this->belongsTo(SeafarerContract::class);
    }
}

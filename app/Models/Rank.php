<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public function ranks()
    {
        return $this->hasMany(self::class, 'department');
    }
}

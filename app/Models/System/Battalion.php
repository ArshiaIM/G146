<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Battalion extends Model
{
     protected $fillable = ['division_id', 'name'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}

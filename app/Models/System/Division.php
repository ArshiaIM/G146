<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['name'];

    public function battalions()
    {
        return $this->hasMany(Battalion::class);
    }
}

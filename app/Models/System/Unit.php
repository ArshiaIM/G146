<?php

namespace App\Models\System;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['battalion_id', 'name', 'type']; // type = گروهان یا ستاد

    public function battalion()
    {
        return $this->belongsTo(Battalion::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

<?php

namespace App\Models\Employee;

use App\Models\System\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'unit_id',
        'employee_type_id',
        'name',
        'rank', // درجه (افسر، درجه‌دار، وظیفه، معاف از رزم، ...)
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function type()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }

     // اگر می‌خواهی، رابطه‌ای برای مرخصی‌هایی که تایید کرده‌اند
    public function approvedLeaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }
}

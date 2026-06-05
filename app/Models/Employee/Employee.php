<?php

namespace App\Models\Employee;

use App\Models\System\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Morilog\Jalali\Jalalian;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'job_title',
        'education_level',
        'degree',
        'address',
        'phone_primary',
        'phone_secondary',
        'phone_landline',
        'division_id',
        'battalion_id',
        'unit_id',
        'organization_id',
        'employee_type_id',
        'name',
        'rank',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function type()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }

    public function rank()
    {
        return $this->belongsTo(EmployeeRank::class, 'employee_rank_id');
    }

    // اگر می‌خواهی، رابطه‌ای برای مرخصی‌هایی که تایید کرده‌اند
    public function approvedLeaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }
    public function getCreatedAtJalaliAttribute()
    {
        return Jalalian::fromDateTime($this->created_at)
            ->format('Y/m/d');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guard extends Model
{
    protected $fillable = ['company_id', 'guard_date', 'notes'];

    protected function casts(): array
    {
        return ['guard_date' => 'date'];
    }

    public function getGuardDateJalaliAttribute(): string
    {
        return \Morilog\Jalali\Jalalian::fromCarbon($this->guard_date)->format('Y/m/d');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(GuardShift::class);
    }
}

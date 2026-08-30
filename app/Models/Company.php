<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['battalion_id', 'name', 'commander_id', 'code'];

    public function battalion(): BelongsTo
    {
        return $this->belongsTo(Battalion::class);
    }

    public function personnel(): HasMany
    {
        return $this->hasMany(Personnel::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    public function guards(): HasMany
    {
        return $this->hasMany(Guard::class);
    }

    // آمار سریع
    public function getTotalPersonnelAttribute(): int
    {
        return $this->personnel()->count();
    }

    public function getPresentTodayAttribute(): int
    {
        return $this->attendances()
            ->whereDate('date', today())
            ->where('status', 'present')
            ->count();
    }
}

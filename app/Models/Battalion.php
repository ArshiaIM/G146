<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Battalion extends Model
{
    protected $fillable = ['name', 'commander', 'code', 'commander_id'];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function personnel(): HasManyThrough
    {
        return $this->hasManyThrough(Personnel::class, Company::class);
    }

    public function getTotalPersonnelAttribute(): int
    {
        return $this->personnel()->count();
    }

    public function getActivePersonnelAttribute(): int
    {
        return $this->personnel()->where('status', 'active')->count();
    }
    public function commanderPersonnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'commander_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Battalion extends Model
{
    protected $fillable = ['name', 'commander', 'code'];

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
}

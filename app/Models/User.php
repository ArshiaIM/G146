<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'company_id',
        'battalion_id',
        'personnel_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'super_admin';
        }

        if ($panel->getId() === 'company') {
            return in_array($this->role, ['company_admin', 'operator', 'battalion_admin'])
                && !is_null($this->company_id);
        }

        return false;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function battalion(): BelongsTo
    {
        return $this->belongsTo(Battalion::class);
    }
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}

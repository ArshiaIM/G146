<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class MedicalRecord extends Model
{
    protected $fillable = [
        'personnel_id', 'sent_at', 'returned_at',
        'diagnosis', 'type', 'rest_days', 'doctor',
        'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sent_at'     => 'datetime',
            'returned_at' => 'datetime',
            'is_active'   => 'boolean',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'sick'    => 'بیمار',
            'injured' => 'مجروح',
            'checkup' => 'معاینه',
            default   => $this->type,
        };
    }

    public function getSentJalaliAttribute(): string
    {
        return Jalalian::fromCarbon($this->sent_at)->format('Y/m/d');
    }

    public function getReturnedJalaliAttribute(): ?string
    {
        return $this->returned_at
            ? Jalalian::fromCarbon($this->returned_at)->format('Y/m/d')
            : null;
    }

    // روزهای بستری
    public function getDaysInMedicalAttribute(): int
    {
        $end = $this->returned_at ?? now();
        return $this->sent_at->diffInDays($end);
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}

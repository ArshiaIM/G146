<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class Absence extends Model
{
     protected $fillable = [
        'personnel_id', 'type', 'started_at',
        'returned_at', 'description', 'is_resolved', 'actions_taken',
    ];

    protected function casts(): array
    {
        return [
            'started_at'  => 'datetime',
            'returned_at' => 'datetime',
            'is_resolved' => 'boolean',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'escaped' ? 'فرار' : 'غیبت';
    }

    public function getStartedJalaliAttribute(): string
    {
        return Jalalian::fromCarbon($this->started_at)->format('Y/m/d');
    }

    // مدت غیبت به روز
    public function getDurationDaysAttribute(): int
    {
        $end = $this->returned_at ?? now();
        return (int) $this->started_at->diffInDays($end);
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}

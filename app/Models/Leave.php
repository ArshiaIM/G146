<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class Leave extends Model
{
    protected $fillable = [
        'personnel_id', 'type', 'start_datetime', 'end_datetime',
        'returned_at', 'reason', 'destination', 'status',
        'approved_by', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime'   => 'datetime',
            'returned_at'    => 'datetime',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'hourly' => 'ساعتی',
            'city'   => 'شهرستان',
            'normal' => 'عادی',
            'reward' => 'پاداشی',
            default  => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'در انتظار',
            'approved' => 'تأیید شده',
            'rejected' => 'رد شده',
            'returned' => 'مراجعت کرده',
            'overdue'  => 'تأخیر در مراجعت',
            default    => $this->status,
        };
    }

    // مدت مرخصی به ساعت
    public function getDurationHoursAttribute(): int
    {
        return $this->start_datetime->diffInHours($this->end_datetime);
    }

    public function getStartJalaliAttribute(): string
    {
        return Jalalian::fromCarbon($this->start_datetime)->format('Y/m/d H:i');
    }

    public function getEndJalaliAttribute(): string
    {
        return Jalalian::fromCarbon($this->end_datetime)->format('Y/m/d H:i');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

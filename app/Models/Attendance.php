<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class Attendance extends Model
{
    protected $fillable = [
        'personnel_id',
        'company_id',
        'date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'present'  => 'حاضر',
            'mission'  => 'مأمور',
            'leave'    => 'مرخصی',
            'medical'  => 'بهداری',
            'absent'   => 'غیبت',
            'arrested' => 'بازداشت',
            'course'   => 'دوره',
            default    => $this->status,
        };
    }

    public function getDateJalaliAttribute(): string
    {
        return $this->date
            ? Jalalian::fromCarbon($this->date)->format('Y/m/d')
            : '';
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

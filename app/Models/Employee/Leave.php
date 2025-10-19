<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $table = 'leaves';

    protected $fillable = [
        'personnel_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'approved_by',
    ];

    // تاریخ‌ها را به صورت Carbon داشته باش
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // رابطه به پرسنل اصلی که مرخصی گرفته
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'personnel_id');
    }

    // رابطه به پرسنلی که مرخصی را تأیید کرده (اگر باشد)
    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'approved_by');
    }
}

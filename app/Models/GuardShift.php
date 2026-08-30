<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuardShift extends Model
{
    protected $fillable = [
        'guard_id',
        'guard_post_id',
        'personnel_id',
        'shift_label',
        'start_time',
        'end_time',
    ];

    public function guardSchedule(): BelongsTo
    {
        return $this->belongsTo(Guard::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(GuardPost::class, 'guard_post_id');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}

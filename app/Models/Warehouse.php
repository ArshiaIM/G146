<?php

namespace App\Models;

use App\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use RequiresApproval;
    protected $fillable = [
        'company_id', 'item_name', 'item_code',
        'quantity', 'unit', 'status',
        'responsible_personnel_id', 'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function responsiblePersonnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'responsible_personnel_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'موجود',
            'low'       => 'کم',
            'out'       => 'تمام شده',
            default     => $this->status,
        };
    }
}

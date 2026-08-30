<?php

namespace App\Models;

use App\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorPool extends Model
{
    use RequiresApproval;
    protected $fillable = [
        'company_id', 'vehicle_type', 'plate_number',
        'serial_number', 'status',
        'driver_personnel_id', 'responsible_personnel_id', 'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'driver_personnel_id');
    }

    public function responsiblePersonnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'responsible_personnel_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'   => 'فعال',
            'repair'   => 'در تعمیر',
            'inactive' => 'از رده خارج',
            default    => $this->status,
        };
    }
}

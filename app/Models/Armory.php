<?php

namespace App\Models;

use App\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Armory extends Model
{
    use RequiresApproval;
    protected $fillable = [
        'company_id', 'weapon_type', 'serial_number',
        'quantity', 'status', 'responsible_personnel_id', 'notes',
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
            'active' => 'فعال',
            'repair' => 'در تعمیر',
            'lost'   => 'مفقود',
            default  => $this->status,
        };
    }
}

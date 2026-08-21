<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationalStrength extends Model
{
    protected $fillable = [
        'company_id',
        'officer_a',
        'officer_b',
        'vazife_officer',
        'nco',
        'vazife_nco',
        'soldier',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getTotal(): int
    {
        return $this->officer_a + $this->officer_b + $this->vazife_officer
            + $this->nco + $this->vazife_nco + $this->soldier;
    }
}

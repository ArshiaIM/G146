<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'model_type', 'model_id', 'action', 'payload',
        'original_data', 'status', 'reason', 'rejection_reason',
        'requested_by', 'approved_by_company_id', 'company_approved_at',
        'approved_by_battalion_id', 'battalion_approved_at',
        'company_id', 'battalion_id',
    ];

    protected function casts(): array
    {
        return [
            'payload'               => 'array',
            'original_data'         => 'array',
            'company_approved_at'   => 'datetime',
            'battalion_approved_at' => 'datetime',
        ];
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedByCompany(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_company_id');
    }

    public function approvedByBattalion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_battalion_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function battalion(): BelongsTo
    {
        return $this->belongsTo(Battalion::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'               => 'Pending',
            'approved_by_company'   => 'Approved by Company',
            'approved_by_battalion' => 'Approved by Battalion',
            'rejected'              => 'Rejected',
            'executed'              => 'Executed',
            default                 => $this->status,
        };
    }

    // اجرای عملیات بعد از تایید نهایی
    public function execute(): void
    {
        $modelClass = $this->model_type;

        match($this->action) {
            'create' => $modelClass::create($this->payload),
            'update' => $modelClass::find($this->model_id)?->update($this->payload),
            'delete' => $modelClass::find($this->model_id)?->delete(),
        };

        $this->update(['status' => 'executed']);
    }
}

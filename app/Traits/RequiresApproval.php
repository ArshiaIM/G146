<?php

namespace App\Traits;

use App\Models\ApprovalRequest;
use App\Models\User;

trait RequiresApproval
{
    // بررسی میکنه آیا عملیات نیاز به تایید داره
    public static function requiresApproval(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['company_admin', 'operator']);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
     protected static function approvalStatus(User $user): string
    {
        return match ($user->role) {

            'operator' => 'pending',

            'company_admin' => 'approved_by_company',

            default => 'pending',

        };
    }

    // ثبت درخواست تایید برای create
    public static function submitCreateRequest(array $data, string $reason = ''): ApprovalRequest
    {
        $user = auth()->user();
        

        return ApprovalRequest::create([
            'model_type'    => static::class,
            'model_id'      => null,
            'action'        => 'create',
            'payload'       => $data,
            'reason'        => $reason,
            'status'        => static::approvalStatus($user),
            'requested_by'  => $user->id,
            'company_id'    => $user->company_id,
            'battalion_id'  => $user->battalion_id,
        ]);
    }

    // ثبت درخواست تایید برای update
    public function submitUpdateRequest(array $data, string $reason = ''): ApprovalRequest
    {
        $user = auth()->user();

        return ApprovalRequest::create([
            'model_type'    => static::class,
            'model_id'      => $this->id,
            'action'        => 'update',
            'payload'       => $data,
            'original_data' => $this->toArray(),
            'reason'        => $reason,
            'status'        => static::approvalStatus($user),
            'requested_by'  => $user->id,
            'company_id'    => $user->company_id,
            'battalion_id'  => $user->battalion_id,
        ]);
    }

    // ثبت درخواست تایید برای delete
    public function submitDeleteRequest(string $reason = ''): ApprovalRequest
    {
        $user = auth()->user();

        return ApprovalRequest::create([
            'model_type'    => static::class,
            'model_id'      => $this->id,
            'action'        => 'delete',
            'original_data' => $this->toArray(),
            'reason'        => $reason,
            'status'        => static::approvalStatus($user),
            'requested_by'  => $user->id,
            'company_id'    => $user->company_id,
            'battalion_id'  => $user->battalion_id,
        ]);
    }
}

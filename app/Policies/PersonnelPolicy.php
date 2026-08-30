<?php

namespace App\Policies;

use App\Models\Personnel;
use App\Models\User;

class PersonnelPolicy
{
    // view list
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'battalion_admin', 'company_admin', 'operator']);
    }

    // view single
    public function view(User $user, Personnel $personnel): bool
    {
        return $this->hasAccess($user, $personnel);
    }

    // create
    public function create(User $user): bool
    {
        return in_array($user->role, ['battalion_admin', 'company_admin', 'operator']);
    }

    // update
    public function update(User $user, Personnel $personnel): bool
    {
        return $this->hasAccess($user, $personnel);
    }

    // delete — operator نمیتونه مستقیم حذف کنه
    public function delete(User $user, Personnel $personnel): bool
    {
        return in_array($user->role, ['battalion_admin', 'company_admin','operator'])
            && $this->hasAccess($user, $personnel);
    }

    private function hasAccess(User $user, Personnel $personnel): bool
    {
        return match($user->role) {
            'super_admin'     => true,
            'battalion_admin' => $personnel->company->battalion_id === $user->battalion_id,
            'company_admin',
            'operator'        => $personnel->company_id === $user->company_id,
            default           => false,
        };
    }
}

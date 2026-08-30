<?php

namespace App\Providers;

use App\Models\Personnel;
use App\Policies\PersonnelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Personnel::class => PersonnelPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

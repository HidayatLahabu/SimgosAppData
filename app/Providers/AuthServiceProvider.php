<?php

namespace App\Providers;

use App\Models\PendaftaranKunjunganModel;
use App\Policies\PendaftaranKunjunganPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PendaftaranKunjunganModel::class => PendaftaranKunjunganPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
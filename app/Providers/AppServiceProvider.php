<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Peminjaman;
use App\Policies\PeminjamanPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
        Gate::define('kelola-buku', function ($user) {
            return $user->hasRole(['admin', 'petugas']); 
            
        });
    }
}

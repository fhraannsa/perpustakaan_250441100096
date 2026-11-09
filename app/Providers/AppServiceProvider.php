<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Definisi Gate kelola-buku untuk admin dan petugas
        Gate::define('kelola-buku', function ($user) {
            return $user->hasRole(['admin', 'petugas']); 
            // Catatan: sesuaikan method pengecekan role jika menggunakan Spatie (misal: $user->hasAnyRole(['admin', 'petugas']))
        });
    }
}

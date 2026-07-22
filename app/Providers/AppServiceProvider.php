<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load global helpers (money(), region()) here as well as via composer's
        // "files" autoload, so they work even when the deploy skips `composer
        // dump-autoload`. The function_exists guards make double-loading safe.
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

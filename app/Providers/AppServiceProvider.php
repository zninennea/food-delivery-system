<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
        
        // Bind ConnectionInterface to Connection for better IDE support
        $this->app->bind(
            \Illuminate\Database\ConnectionInterface::class,
            \Illuminate\Database\MySqlConnection::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Force Google config if not set
        if (empty(config('services.google.client_id'))) {
            config([
                'services.google.client_id' => env('GOOGLE_CLIENT_ID'),
                'services.google.client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'services.google.redirect' => env('GOOGLE_REDIRECT_URI'),
            ]);
        }
    }
}
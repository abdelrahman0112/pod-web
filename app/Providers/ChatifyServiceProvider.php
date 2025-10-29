<?php

namespace App\Providers;

use App\Services\ChatifyMessenger;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ChatifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('chatify', function ($app) {
            return new ChatifyMessenger;
        });

        // Register the ChatifyMessenger service for the facade
        $this->app->singleton('ChatifyMessenger', function ($app) {
            return new ChatifyMessenger;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load our custom routes after vendor service provider
        $this->app->booted(function () {
            $this->loadCustomRoutes();
        });
    }

    /**
     * Load custom Chatify routes
     */
    protected function loadCustomRoutes(): void
    {
        Route::group([
            'prefix' => config('chatify.routes.prefix'),
            'namespace' => config('chatify.routes.namespace'),
            'middleware' => config('chatify.routes.middleware'),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/chatify/web.php'));
        });

        Route::group([
            'prefix' => config('chatify.api_routes.prefix'),
            'namespace' => config('chatify.api_routes.namespace'),
            'middleware' => config('chatify.api_routes.middleware'),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/chatify/api.php'));
        });
    }
}

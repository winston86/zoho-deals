<?php

namespace Winston86\ZohoDeals;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Winston86\ZohoDeals\View\Components\ZohoDeals;
use Winston86\ZohoDeals\Interfaces\ZohoApiInterface;
use Winston86\ZohoDeals\Services\ZohoApiService;
use Illuminate\Support\Facades\Route;

class ZohoDealsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Automatically merge the config so your package can use config('zoho.client_id')
        $this->mergeConfigFrom(__DIR__.'/config/zoho.php', 'zoho');

        // Bind the Interface to the Service
        $this->app->singleton(ZohoApiInterface::class, function ($app) {
            return new ZohoApiService();
        });
        // dd('isisisi');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // Tell Laravel where the views are located
        // Access them via "zoho-deals::name"
        $this->loadViewsFrom(__DIR__.'/resources/views', 'zoho-deals');

        // Register the Blade Component
        // This allows you to use <x-zoho-deals /> in any blade file
        Blade::component(ZohoDeals::class, 'zoho-deals');

        // Optional: Allow users to publish your views to their own project
        if ($this->app->runningInConsole()) {
            // Allow users to run: php artisan vendor:publish --tag=zoho-config
            $this->publishes([
                __DIR__.'/config/zoho.php' => config_path('zoho.php'),
            ], 'zoho-config');
            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/zoho-deals'),
            ], 'zoho-deals-views');
        }
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }
    
    protected function routeConfiguration()
    {
        return [
            'prefix' => config('zoho.route_prefix', 'api/zoho'),
            'middleware' => config('zoho.middleware', ['api']),
        ];
    }
}

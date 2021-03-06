<?php

namespace Thotam\ThotamAuth;

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thotam\ThotamAuth\Http\Livewire\AuthLivewire;
use Thotam\ThotamAuth\Console\Commands\HR_Key_Sync_Command;

class ThotamAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'thotam-auth');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'thotam-auth');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Route::domain('beta.' . env('APP_DOMAIN', 'cpc1hn.com.vn'))->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('thotam-auth.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/thotam-auth'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/thotam-auth'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/thotam-auth'),
            ], 'lang');*/

            // Publishing the Fortify files.
            $this->publishes([
                __DIR__.'/../stubs/Fortify/CreateNewUser.php' => app_path('Actions/Fortify/CreateNewUser.php'),
                __DIR__.'/../stubs/Fortify/FortifyServiceProvider.php' => app_path('Providers/FortifyServiceProvider.php'),
                __DIR__.'/../stubs/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            ], 'fortify');

            // Registering package commands.
            $this->commands([
                HR_Key_Sync_Command::class,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Seed Service Provider need on boot() method
        |--------------------------------------------------------------------------
        */
        $this->app->register(SeedServiceProvider::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'thotam-auth');

        // Register the main class to use with the facade
        $this->app->singleton('thotam-auth', function () {
            return new ThotamAuth;
        });

        if (class_exists(Livewire::class)) {
            Livewire::component('thotam-auth::auth-livewire', AuthLivewire::class);
        }
    }
}

<?php

namespace MBonaldo\ComingSoon;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class ComingSoonServiceProvider extends ServiceProvider
{
    /**
     * The console commands.
     *
     * @var bool
     */
    protected $commands = [
        'MBonaldo\ComingSoon\Commands\ComingSoonOff',
        'MBonaldo\ComingSoon\Commands\ComingSoonOn',
    ];
	
	
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mbonaldo');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mbonaldo');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        // Publishing is only necessary when using the CLI.
        //if ($this->app->runningInConsole()) {
        //    $this->bootForConsole();
        //}
		
	   $this->app['router']->namespace('MBonaldo\\ComingSoon\\Controllers')
			->middleware(['web'])
			->group(function () {
				$this->loadRoutesFrom(__DIR__ . '/routes/web.php');
			});

        $this->publishes([
            __DIR__.'/../config/comingsoon.php' => config_path('comingsoon.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/comingsoon.php', 'comingsoon');

		$this->commands($this->commands);
		
        // Register the service the package provides.
        $this->app->singleton('comingsoon', function ($app) {
            return new ComingSoon;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['comingsoon'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/comingsoon.php' => config_path('comingsoon.php'),
        ], 'comingsoon.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/mbonaldo'),
        ], 'comingsoon.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mbonaldo'),
        ], 'comingsoon.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mbonaldo'),
        ], 'comingsoon.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}

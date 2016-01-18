<?php
namespace YigitCukuren\Optimum7;

use Illuminate\Support\ServiceProvider;

class Optimum7ServiceProvider extends ServiceProvider {

    protected $commands = [
        'YigitCukuren\Optimum7\Commands\Optimum7',
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/Optimum7.php' => config_path('Optimum7.php')]);
        $this->loadViewsFrom(__DIR__.'/views', 'optimum7');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/optimum7'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('YigitCukuren\Optimum7\Controllers\Optimum7Controller');
        $this->commands($this->commands);
    }

}
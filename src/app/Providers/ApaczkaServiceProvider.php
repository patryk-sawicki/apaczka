<?php

namespace PatrykSawicki\Apaczka\app\Providers;

use Illuminate\Support\ServiceProvider;
class ApaczkaServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath($raw = __DIR__ . '/../../');

//        include $path . '/routes/web.php';

        if(file_exists($this->app->databasePath() . '/config/apaczka.php') == false){
            $this->publishes([$path . '/config/apaczka.php' => config_path('apaczka.php')], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $path = realpath($raw = __DIR__ . '/../../');
        $this->mergeConfigFrom($path . '/config/apaczka.php', 'apaczka');
    }
}

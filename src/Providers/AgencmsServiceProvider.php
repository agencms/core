<?php

namespace Silvanite\Agencms\Providers;

use Illuminate\Support\ServiceProvider;
use Silvanite\Agencms\Config;

class AgencmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register the Config module
     *
     * @param string $IoC name of the container
     * @return Silvanite\Agencms\Config
     */
    private function registerConfig($container = "AgencmsConfig")
    {
        $this->app->bind($container, function(){
            return new Config;
        });
    }
}

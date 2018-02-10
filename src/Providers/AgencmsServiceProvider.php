<?php

namespace Silvanite\Agencms\Providers;

use Silvanite\Agencms\Config;
use Silvanite\Brandenburg\Policy;
use Illuminate\Support\Facades\Gate;
use Silvanite\Brandenburg\Permission;
use Illuminate\Support\ServiceProvider;
use Silvanite\AgencmsBlog\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Silvanite\Agencms\Listeners\EloquentListener;
use Silvanite\Brandenburg\Traits\ValidatesPermissions;

class AgencmsServiceProvider extends ServiceProvider
{
    use ValidatesPermissions;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerApiRoutes();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPermissions();
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
        $this->app->bind($container, function () {
            return new Config;
        });
    }

    /**
     * Load Api Routes into the application
     *
     * @return void
     */
    private function registerApiRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    /**
     * Register default package related permissions
     *
     * @return void
     */
    private function registerPermissions()
    {
        collect([
            'admin_access',
        ])->map(function ($permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if ($this->nobodyHasAccess($permission)) {
                    return true;
                }

                return $user->hasRoleWithPermission($permission);
            });
        });
    }
}

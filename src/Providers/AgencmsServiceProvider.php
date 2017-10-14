<?php

namespace Silvanite\Agencms\Providers;

use Illuminate\Support\ServiceProvider;
use Silvanite\Agencms\Config;
use Silvanite\Agencms\Listeners\EloquentListener;
use Silvanite\AgencmsBlog\BlogCategory;
use Gate;
use Event;
use Silvanite\Brandenburg\Policy;
use Silvanite\Brandenburg\Permission;
use Illuminate\Database\Eloquent\Model;

class AgencmsServiceProvider extends ServiceProvider
{

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
        $this->app->bind($container, function(){
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
        ])->map(function($permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if ($this->nobodyHasAccess($permission)) return true;

                return $user->hasRoleWithPermission($permission);
            });
        });
    }

    /**
     * If nobody has this permission, grant access to everyone
     * This avoids you from being locked out of your application
     *
     * @param string $permission
     * @return boolean
     */
    private function nobodyHasAccess($permission)
    {
        if (!$requestedPermission = Permission::find($permission)) return true;

        return !$requestedPermission->hasUsers();
    }
}
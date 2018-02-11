<?php

namespace Silvanite\Agencms\Providers;

use Silvanite\Agencms\Config;
use Silvanite\Brandenburg\Policy;
use Illuminate\Support\Facades\Gate;
use Silvanite\Brandenburg\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Silvanite\AgencmsBlog\BlogCategory;
use Silvanite\Agencms\Support\RenderEngine;
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
        $this->bootViews();
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
        $this->registerRenderEngine();
        $this->registerBladeExtensions();
    }

    /**
     * Load Agencms views used for rendering content
     *
     * @return void
     */
    private function bootViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'agencms');
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/agencms'),
        ], 'views');
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
     * Register our Render Engine facade used to renderin CMS content in blade
     *
     * @return void
     */
    private function registerRenderEngine()
    {
        $this->app->bind('agencms-render-engine', function () {
            return new RenderEngine;
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

    /**
     * Register our blade extensions to easily render CMS content
     *
     * @return void
     */
    private function registerBladeExtensions()
    {
        Blade::directive('repeater', function ($expression = []) {
            return "<?php echo \RenderEngine::renderRepeater({$expression}); ?>";
        });

        Blade::directive('field', function ($expression = []) {
            return "<?php echo \RenderEngine::renderField({$expression}); ?>";
        });
    }
}

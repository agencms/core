<?php

namespace Agencms\Core\Providers;

use Agencms\Core\Config;
use Barryvdh\Cors\HandleCors;
use Silvanite\Brandenburg\Policy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Silvanite\Brandenburg\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Agencms\Core\Commands\Install;
use Agencms\Blog\BlogCategory;
use Illuminate\Support\ServiceProvider;
use Agencms\Core\Support\RenderEngine;
use Agencms\Core\Listeners\EloquentListener;
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
        $this->bootCommands();
        $this->enableCors();
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

    private function enableCors()
    {
        Route::aliasMiddleware('cors', HandleCors::class);
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

    private function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
            ]);
        }
    }

    /**
     * Register the Config module
     *
     * @param string $IoC name of the container
     * @return Agencms\Core\Config
     */
    private function registerConfig()
    {
        $this->app->bind("agencms-config", function () {
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

        Blade::directive('render', function ($expression = []) {
            return "<?php echo \RenderEngine::render({$expression}); ?>";
        });
    }
}

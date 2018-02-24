<?php 

namespace Silvanite\Agencms;

class Config
{
    const TYPE_COLLECTION = 'collection';
    const TYPE_SINGLE = 'single';
    const TYPE_HIDDEN = 'hidden';

    protected $routes;
    protected $plugins;

    /**
     *
     * @param Array $routes
     * @param Array $plugins
     */
    public function __construct(Array $routes = [], Array $plugins = [])
    {
        $this->routes = $routes;
        $this->plugins = $plugins;
    }

    /**
     * Add a plugin to the config loader. The loader will iterate through all
     * plugins and load each one as middlware. This allows other packages to
     * include their configurations in the /agencms/config route.
     *
     * @param string $plugin
     * @return boolean
     */
    public function registerPlugin(string $plugin)
    {
        if (!in_array($plugin, $this->plugins)) {
            $this->plugins[] = $plugin;
        }

        return true;
    }

    /**
     * Return an array of all registered plugins
     *
     * @return Array
     */
    public function plugins()
    {
        return $this->plugins;
    }

    /**
     * Register a new route for the CMS Rest endpoint
     *
     * @param Silvanite\Agencms\Route $route
     * @return Illuminate\Support\Collection
     */
    public function registerRoute(Route $route)
    {
        return $this->routes[$route->slug()] = $route->get();
    }

    /**
     * Append additional groups to an existing Route
     *
     * @param Silvanite\Agencms\Route $route
     * @return Illuminate\Support\Collection
     */
    public function appendRoute(Route $route)
    {
        if (!$originalRoute = optional($this->routes)[$route->slug()]) {
            return "Route {$route->slug()} not found";
        }

        return $this->routes[$route->slug()]['groups'] =
            $originalRoute['groups']->merge($route->get()['groups']);
    }

    /**
     * Return all registered routes
     *
     * @return Illuminate\Support\Collection
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * Provides a complete snapshot of all registered configration settings
     *
     * @return Array
     */
    public function all()
    {
        $config = [];

        $config['routes'] = $this->routes;

        return $config;
    }
}

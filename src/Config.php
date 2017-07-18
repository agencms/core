<?php 

namespace Silvanite\Agencms;

class Config
{
    public const TYPE_COLLECTION = 'collection';
    public const TYPE_SINGLE = 'single';
    public const TYPE_HIDDEN = 'hidden';

    protected $routes;

    /**
     *
     * @param Array $routes
     */
    public function __construct(Array $routes = [])
    {
        $this->routes = $routes;
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

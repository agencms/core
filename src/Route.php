<?php

namespace Silvanite\Agencms;

class Route
{
    private $route;

    const ROUTE_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE',
    ];

    protected function __construct()
    {
        $this->route = [];
    }

    /**
     * Initialise a new route. This must be called before any other method on the class
     *
     * @param string $slug
     * @param string $name
     * @param string|Array $endpoints
     * @param string $type
     * @return Silvanite\Agencms\Route
     */
    public static function init($slug, $name, $endpoints = [], $type = Config::TYPE_COLLECTION)
    {
        $instance = new static();

        $instance->route = [];
        $instance->route['slug'] = $slug;
        $instance->route['name'] = $name;
        $instance->route['type'] = $type;
        $instance->route['endpoints'] = self::makeEndpoints($endpoints);
        $instance->route['groups'] = collect([]);
        $instance->route['icon'] = 'filter_list';

        return $instance;
    }

    /**
     * Initialise a new single record style route.
     *
     * @param string $slug
     * @param string $name
     * @param string|Array $endpoints
     * @return Silvanite\Agencms\Route
     */
    public static function initSingle($slug, $name, $endpoints = [])
    {
        return self::init($slug, $name, $endpoints, Config::TYPE_SINGLE);
    }

    /**
     * Creates a valid array of required endpoints
     *
     * @param string $endpoints
     * @return Array
     */
    private static function makeEndpoints($endpoints)
    {
        if (is_string($endpoints)) {
            return self::makeEndpointsFromString($endpoints, self::ROUTE_METHODS);
        }

        return $endpoints;
    }

    /**
     * Creates a valid array of endpoints from a single endpoint
     *
     * @param string $endpoint
     * @param Array $methods
     * @return Array
     */
    private static function makeEndpointsFromString($endpoint, $methods)
    {
        return collect($methods)->map(function ($method) use ($endpoint) {
            return [$method => $endpoint];
        })->collapse();
    }

    /**
     * Registers a new API endpoint group
     *
     * @param Silvanite\Agencms\Group ...$groups
     * @return Silvanite\Agencms\Route
     */
    public function addGroup(...$groups)
    {
        collect($groups)->map(function ($group) {
            $this->route['groups'][] = $group->get();
        });

        return $this;
    }

    public function hidden()
    {
        $this->route['type'] = Config::TYPE_HIDDEN;

        return $this;
    }

    /**
     * Return the slug for the route
     *
     * @return string
     */
    public function slug()
    {
        return $this->route['slug'];
    }

    /**
     * Set the CMS Icon for this Route
     *
     * @param string $key
     * @return Silvanite\Agencms\Route
     */
    public function icon(string $key)
    {
        $this->route['icon'] = $key;
        
        return $this;
    }

    /**
     * Get the entire route configuration
     *
     * @return Array
     */
    public function get()
    {
        return $this->route;
    }
}

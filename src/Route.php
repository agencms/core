<?php

namespace Silvanite\Agencms;

class Route
{
    public static $route;
    public static $instance;

    protected const ROUTE_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE'
    ];

    protected static function instance()
    {
        if (static::$instance === null) 
        {
            static::$instance = new Route;
        }

        return static::$instance;
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
        self::$route = collect([]);
        self::$route->slug = $slug;
        self::$route->name = $name;
        self::$route->type = $type;
        self::$route->endpoints = self::makeEndpoints($endpoints);
        self::$route->fields = collect([]);

        return self::instance();
    }

    /**
     * Creates a valid array of required endpoints
     *
     * @param string $endpoints
     * @return Array
     */
    private static function makeEndpoints($endpoints)
    {
        if (is_string($endpoints)) 
            return self::makeEndpointsFromString($endpoints, self::ROUTE_METHODS);

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
        });
    }

    /**
     * Registers a new API endpoint field
     *
     * @param string $key
     * @param string $name
     * @param string $type
     * @param boolean $readonly
     * @return Silvanite\Agencms\Route
     */
    public static function addField($key, $name, $type, $readonly = false, $size = 12)
    {
        self::$route->put(
            $key, collect()->put('key', $key)
                           ->put('name', $name)
                           ->put('type', $type)
                           ->put('readonly', $readonly)
                           ->put('size', $size)
        );

        return self::instance();
    }

    /**
     * Return the slug for the route
     *
     * @return string
     */
    public static function slug()
    {
        return self::$route->slug;
    }

    /**
     * Get the entire route configuration
     *
     * @return Illuminate\Support\Collection
     */
    public static function get()
    {
        return self::$route;
    }
}
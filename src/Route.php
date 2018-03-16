<?php

namespace Agencms\Core;

use Illuminate\Support\Facades\Gate;

class Route
{
    private $route;

    const ROUTE_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE',
    ];

    const ROUTE_CRUD_PERMISSION = [
        'GET' => 'read',
        'POST' => 'create',
        'PUT' => 'update',
        'DELETE' => 'delete',
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
     * @return Agencms\Core\Route
     */
    public static function init($slug, $name, $endpoints = null, $type = Config::TYPE_COLLECTION)
    {
        $instance = new static();

        $instance->route = [];

        if (is_array($name)) {
            $section = key($name);
            $name = $name[$section];
        } else {
            $section = $name;
        }

        $instance->route['slug'] = $slug;
        $instance->route['name'] = $name;
        $instance->route['section'] = $section;
        $instance->route['type'] = $type;
        $instance->route['endpoints'] = self::makeEndpoints($endpoints);
        $instance->route['groups'] = collect([]);
        $instance->route['icon'] = 'filter_list';

        return $instance;
    }

    /**
     * Creates a new Route instance for merging into an existing route collection
     *
     * @param string $slug
     * @return Agencms\Core\Route
     */
    public static function load(string $slug)
    {
        $instance = new static();

        $instance->route = [];
        $instance->route['slug'] = $slug;
        $instance->route['groups'] = collect([]);

        return $instance;
    }

    /**
     * Initialise a new single record style route.
     *
     * @param string $slug
     * @param string $name
     * @param string|Array $endpoints
     * @return Agencms\Core\Route
     */
    public static function initSingle($slug, $name, $endpoints = [])
    {
        return self::init($slug, $name, $endpoints, Config::TYPE_SINGLE);
    }

    /**
     * Creates a valid array of required endpoints based on the user's permissions
     *
     * @param string $permission
     * @param string $endpoint
     * @return Array
     */
    public static function generateCrudEndpoints(string $permission, string $endpoint)
    {
        return collect(self::ROUTE_CRUD_PERMISSION)
            ->map(function ($suffix, $method) use ($permission, $endpoint) {
                if (Gate::allows("{$permission}_{$suffix}")) {
                    return [$method => $endpoint];
                }
            })->collapse();
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
     * @param Agencms\Core\Group ...$groups
     * @return Agencms\Core\Route
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
     * @return Agencms\Core\Route
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

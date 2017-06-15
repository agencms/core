<?php

namespace Silvanite\Agencms;

class Route
{
    protected const ROUTE_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE'
    ];

    protected $slug;
    protected $name;
    protected $type;
    protected $endpoints;
    protected $fields;

    /**
     *
     * @param string $slug
     * @param string $name
     * @param string|Array $endpoints
     */
    public function __construct($slug, $name, $endpoints = [], $type = Config::TYPE_COLLECTION)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->type = $type;
        $this->endpoints = $this->makeEndpoints($endpoints);
        $this->fields = collect([]);
    }

    /**
     * Creates a valid array of required endpoints
     *
     * @param string $endpoints
     * @return Array
     */
    private function makeEndpoints($endpoints)
    {
        if (is_string($endpoints)) 
            return $this->makeEndpointsFromString($endpoints, self::ROUTE_METHODS);

        return $endpoints;
    }

    /**
     * Creates a valid array of endpoints from a single endpoint
     *
     * @param string $endpoint
     * @param Array $methods
     * @return Array
     */
    private function makeEndpointsFromString($endpoint, $methods)
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
    public function addField($key, $name, $type, $readonly = false, $size = 12)
    {
        $this->fields->put(
            $key, collect()->put('key', $key)
                           ->put('name', $name)
                           ->put('type', $type)
                           ->put('readonly', $readonly)
                           ->put('size', $size)
        );

        return $this;
    }

    /**
     * Get the entire route configuration
     *
     * @return Illuminate\Support\Collection
     */
    public function get()
    {
        return collect([
            'slug' => $this->slug,
            'endpoints' => $this->endpoints->collapse(),
            'name' => $this->name,
            'type' => $this->type,
            'fields' => $this->fields
        ]);
    }

    /**
     * Dynamic getter for route properties
     *
     * @param string $key
     * @return void
     */
    public function __get($key)
    {
        if (property_exists($this, $key))
            return $this->$key;

        return false;
    }
}
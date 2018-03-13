<?php

namespace Agencms\Core;

class Relationship
{
    protected $relationship;

    /**
     * Set the defaults for a new Relationship
     */
    protected function __construct()
    {
        $this->relationship = [
            'model' => ''
        ];
    }

    /**
     * Initialise a new relationship instance. 
     * This must be the first call (or a helper method).
     *
     * @param string $model
     * @return Agencms\Core\Relationship
     */
    public static function make($model)
    {
        $instance = new static();

        $instance->relationship['model'] = $model;

        return $instance;
    }

    /**
     * Return the relationship field
     *
     * @return array
     */
    public function get()
    {
        return $this->relationship;
    }
}

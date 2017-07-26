<?php

namespace Silvanite\Agencms;

use Silvanite\Agencms\Field;

class Group
{
    protected $group;

    /**
     * Set the defaults for a new Group object
     */
    protected function __construct()
    {
        $this->group = [
            'name' => 'New Group',
            'size' => 12,
            'fields' => collect([])
        ];
    }

    /**
     * Set the display column width of the group
     *
     * @param string $name
     * @param int $size
     * @return Silvanite\Agencms\Group
     */
    public static function size(string $name, int $size = 12)
    {
        $instance = new static();

        $instance->group['name'] = $name;
        $instance->group['size'] = $size;

        return $instance;
    }

    /**
     * Helper methods for size
     *
     * @param string $name
     * @return Silvanite\Agencms\Group
     */
    public static function tiny(string $name)
    {
        return self::size($name, 2);
    }

    public static function small($name)
    {
        return self::size($name, 4);
    }

    public static function medium($name)
    {
        return self::size($name, 6);
    }

    public static function large($name)
    {
        return self::size($name, 8);
    }

    public static function full($name)
    {
        return self::size($name, 12);
    }

    /**
     * Add fields to the current group. Accepts a comma separated list of Fields
     *
     * @param Field ...$fields
     * @return Silvanite\Agencms\Group
     */
    public function addField(Field ...$fields)
    {
        collect($fields)->map(function ($field) {
            $this->group['fields']->put($field->key(), $field->get());
        });

        return $this;
    }

    /**
     * Get the entire group configuration including all fields
     *
     * @return Array
     */
    public function get()
    {
        return $this->group;
    }
}
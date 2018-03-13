<?php

namespace Agencms\Core;

use Agencms\Core\Field;

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
            'key' => '',
            'size' => 12,
            'repeater' => false,
            'fields' => collect([]),
            'groups' => collect([])
        ];
    }

    /**
     * Set the display column width of the group
     *
     * @param string $name
     * @param int $size
     * @return Agencms\Core\Group
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
     * @return Agencms\Core\Group
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
     * Defined a sub-group as a repeater group and assigns a key for saving
     *
     * @param string $key
     * @return Agencms\Core\Group
     */
    public function repeater(string $key)
    {
        $this->key($key);
        $this->group['repeater'] = true;

        return $this;
    }

    /**
     * Assign a specific key to a sub-group
     *
     * @param [type] $key
     * @return void
     */
    public function key($key)
    {
        $this->group['key'] = $key;

        return $this;
    }

    /**
     * Add fields to the current group. Accepts a comma separated list of Fields
     *
     * @param Field ...$fields
     * @return Agencms\Core\Group
     */
    public function addField(Field ...$fields)
    {
        collect($fields)->map(function ($field) {
            $this->group['fields']->put($field->key(), $field->get());
        });

        return $this;
    }

    /**
     * Sub-groups are used to define collections of repeatable fields which can be
     * inserted, deleted and re-ordered by the user.
     *
     * @param Agencms\Core\Group ...$groups
     * @return Agencms\Core\Group
     */
    public function addGroup(Group ...$groups)
    {
        collect($groups)->map(function ($group) {
            $this->group['groups'][] = $group->get();
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
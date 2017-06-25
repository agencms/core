<?php

namespace Silvanite\Agencms;

class Field
{
    protected $field;

    /**
     * Set the defaults for a new Field
     */
    protected function __construct()
    {
        $this->field = [
            'required' => false,
            'readonly' => false,
            'key' => 'new',
            'name' => 'New Field',
            'type' => 'string',
            'size' => 12,
            'list' => false,
            'max' => 1
        ];
    }

    /**
     * Initialise a new field instance. This must be the first call (or a helper method).
     *
     * @param string $type
     * @param string $key
     * @param string $name
     * @return Silvanite\Agencms\Field
     */
    protected static function init(string $type = 'string', string $key = null, string $name = null)
    {
        $instance = new static();

        $instance->key($key);
        $instance->field['type'] = $type;

        if ($name) $instance->field['name'] = $name;

        return $instance;
    }

    /**
     * Helper methods to initialise a new field of specific types.
     * These should be used to initialise a new field.
     *
     * @param string $key
     * @param string $name
     * @return Silvanite\Agencms\Field
     */
    public static function string(string $key = null, string $name = null)
    {
        return self::init('string', $key, $name);
    }

    public static function number(string $key = null, string $name = null)
    {
        return self::init('number', $key, $name);
    }

    public static function boolean(string $key = null, string $name = null)
    {
        return self::init('boolean', $key, $name);
    }

    public static function date(string $key = null, string $name = null)
    {
        return self::init('date', $key, $name);
    }

    public static function image(string $key = null, string $name = null)
    {
        return self::init('image', $key, $name);
    }

    /**
     * Allow multiple image uploads on an image field
     *
     * @param int $number
     * @return Silvanite\Agencms\Field
     */
    public function multi(int $number = 50)
    {
        $this->field['max'] = $number;

        return $this;
    }

    /**
     * Only allow a single image to be uploaded to an image field
     *
     * @return Silvanite\Agencms\Field
     */
    public function single()
    {
        return $this->multi(1);
    }

    /**
     * Determine if a value is required for this field. This will trigger client
     * side validation.
     *
     * @return Silvanite\Agencms\Field
     */
    public function required()
    {
        $this->field['required'] = true;

        return $this;
    }

    /**
     * Determine if a value is optional for this field. This will trigger client
     * side validation
     *
     * @return Silvanite\Agencms\Field
     */
    public function optional()
    {
        $this->field['required'] = false;

        return $this;
    }

    /**
     * Determine if a field should be displayed in the list view
     *
     * @return Silvanite\Agencms\Field
     */
    public function list()
    {
        $this->field['list'] = true;

        return $this;
    }

    /**
     * Allow/do not allow editing of this field in the admin UI
     *
     * @param boolean $value
     * @return Silvanite\Agencms\Field
     */
    public function readonly(bool $value = true)
    {
        $this->field['readonly'] = $value;

        return $this;
    }

    /**
     * Set or get the key of a field. Returns chainable instance if a key is 
     * supplied, otherwise returns a string of the key.
     *
     * @param string $key
     * @return Silvanite\Agencms\Field or string
     */
    public function key(string $key = null)
    {
        if ($key) {
            $this->field['key'] = $key;

            return $this;
        }

        return $this->field['key'];
    }

    /**
     * Sets the display name of a field
     *
     * @param string $name
     * @return Silvanite\Agencms\Field
     */
    public function name(string $name = null)
    {
        if ($name) $this->field['name'] = $name;

        return $this;
    }

    /**
     * Returns an array of this field's configuration
     *
     * @return Array
     */
    public function get()
    {
        return $this->field;
    }

    /**
     * Defines the display width of the item within the admin UI
     *
     * @param integer $size
     * @return Silvanite\Agencms\Field
     */
    public function size($size = 12)
    {
        $this->field['size'] = $size;

        return $this;
    }

    /**
     * Helper methods to set the size of a field within the adminUI
     *
     * @return Silvanite\Agencms\Field
     */
    public function tiny()
    {
        return $this->size(2);
    }

    public function small()
    {
        return $this->size(4);
    }

    public function medium()
    {
        return $this->size(6);
    }

    public function large()
    {
        return $this->size(8);
    }

    public function full()
    {
        return $this->size(12);
    }
}

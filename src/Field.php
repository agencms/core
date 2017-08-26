<?php

namespace Silvanite\Agencms;

use Silvanite\Agencms\Option;
use Silvanite\Agencms\Relationship;

class Field
{
    protected $field;

    public const MODE_CHECKBOX = 'checkbox';
    public const MODE_SELECT = 'select';
    public const MODE_SLUG = 'slug';

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
            'min' => 0,
            'max' => 0,
            'rows' => 1,
            'choices' => collect([]),
            'mode' => '',
            'related' => collect([]),
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
        $instance = self::init('string', $key, $name);

        $instance->minLength(0);
        $instance->maxLength(0);

        return $instance;
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

    public static function select(string $key = null, string $name = null)
    {
        return self::init('select', $key, $name);
    }

    public static function related(string $key = null, string $name = null)
    {
        return self::init('related', $key, $name);
    }

    /**
     * Set the number of rows allowed (e.g. text fields)
     *
     * @param int $rows
     * @return Silvanite\Agencms\Field
     */
    public function rows(int $rows)
    {
        $this->field['rows'] = $rows;

        return $this;
    }

    /**
     * Helper method to set a text area to be single-line.
     *
     * @return Silvanite\Agencms\Field
     */
    public function singleline()
    {
        return $this->rows(1);
    }

    /**
     * Helper method to set a text area to be multi-line.
     *
     * @param int $rows
     * @return Silvanite\Agencms\Field
     */
    public function multiline(int $rows = 5)
    {
        return $this->rows($rows);
    }

    /**
     * Allow multiple image uploads on an image field
     *
     * @param int $number
     * @return Silvanite\Agencms\Field
     */
    public function multiple(int $number = 50)
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
        return $this->multiple(1);
    }

    /**
     * Limit input to a maximum of X chars
     *
     * @param int $number
     * @return Silvanite\Agencms\Field
     */
    public function maxLength(int $number = 50)
    {
        $this->field['max'] = $number;

        return $this;
    }

    /**
     * Require the input to be at least X chars
     *
     * @param int $number
     * @return Silvanite\Agencms\Field
     */
    public function minLength(int $number = 0)
    {
        $this->field['min'] = $number;

        return $this;
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

    /**
     * Set the available choices for a select field as an array of key value pairs
     *
     * @param Option $choices
     * @return Silvanite\Agencms\Field
     */
    public function addOption(Option ...$choices)
    {
        collect($choices)->map(function ($choice) {
            $this->field['choices']->push($choice->get());
        });

        return $this;
    }

    /**
     * Set the available choices for a select field as an array of key value pairs
     * taken directly from a supplied array
     *
     * @param array $choices
     * @return Silvanite\Agencms\Field
     */
    public function addOptions(array $choices)
    {
        collect($choices)->map(function ($choice) {
            $this->field['choices']->push([
                'value' => strtolower($choice),
                'text' => $choice
            ]);
        });

        return $this;
    }

    /**
     * Define the target model for a relationship field
     *
     * @param Relationship $model
     * @return Silvanite\Agencms\Field
     */
    public function model(Relationship $model)
    {
        $this->field['related'] = $model->get();

        return $this;
    }

    /**
     * Set the mode for select fields
     *
     * @param string $mode
     * @return Silvanite\Agencms\Field
     */
    public function mode(string $mode = self::MODE_SELECT)
    {
        $this->field['mode'] = $mode;

        return $this;
    }

    /**
     * Helper method to set the select mode to checkboxes
     *
     * @return Silvanite\Agencms\Field
     */
    public function checkbox()
    {
        return $this->mode(self::MODE_CHECKBOX);
    }

    /**
     * Helper method to set the select mode to dropdown
     *
     * @return Silvanite\Agencms\Field
     */
    public function dropdown()
    {
        return $this->mode(self::MODE_SELECT);
    }

    /**
     * Helper method to set text input mode to slug
     *
     * @return Silvanite\Agencms\Field
     */
    public function slug()
    {
        return $this->mode(self::MODE_SLUG);
    }
}

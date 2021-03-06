<?php

namespace Agencms\Core;

use Agencms\Core\Option;
use Agencms\Core\Relationship;

class Field
{
    protected $field;

    const MODE_CHECKBOX = 'checkbox';
    const MODE_SELECT = 'select';
    const MODE_TAGS = 'tags';
    const MODE_SLUG = 'slug';

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
            'list' => 0,
            'min' => 0,
            'max' => 0,
            'rows' => 1,
            'choices' => collect([]),
            'mode' => '',
            'link' => '',
            'related' => collect([]),
            'ratio' => '',
            'imagesize' => null,
        ];
    }

    /**
     * Initialise a new field instance. This must be the first call (or a helper method).
     *
     * @param string $type
     * @param string $key
     * @param string $name
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
     */
    public function rows(int $rows)
    {
        $this->field['rows'] = $rows;

        return $this;
    }

    /**
     * Helper method to set a text area to be single-line.
     *
     * @return Agencms\Core\Field
     */
    public function singleline()
    {
        return $this->rows(1);
    }

    /**
     * Helper method to set a text area to be multi-line.
     *
     * @param int $rows
     * @return Agencms\Core\Field
     */
    public function multiline(int $rows = 5)
    {
        return $this->rows($rows);
    }

    /**
     * Allow multiple image uploads on an image field
     *
     * @param int $number
     * @return Agencms\Core\Field
     */
    public function multiple(int $number = 50)
    {
        $this->field['max'] = $number;

        return $this;
    }

    /**
     * Only allow a single image to be uploaded to an image field
     *
     * @return Agencms\Core\Field
     */
    public function single()
    {
        return $this->multiple(1);
    }

    /**
     * Limit input to a maximum of X chars
     *
     * @param int $number
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
     */
    public function optional()
    {
        $this->field['required'] = false;

        return $this;
    }

    /**
     * Determine if a field should be displayed in the list view
     *
     * @param int $position
     * @return Agencms\Core\Field
     */
    public function list(int $position = 50)
    {
        $this->field['list'] = $position;

        return $this;
    }

    /**
     * Allow/do not allow editing of this field in the admin UI
     *
     * @param boolean $value
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field or string
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
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
     */
    public function size($size = 12)
    {
        $this->field['size'] = $size;

        return $this;
    }

    /**
     * Helper methods to set the size of a field within the adminUI
     *
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
     */
    public function addOptions(array $choices)
    {
        collect($choices)->map(function ($choice) {
            $this->field['choices']->push($choice);
        });

        return $this;
    }

    /**
     * Define the target model for a relationship field
     *
     * @param Relationship $model
     * @return Agencms\Core\Field
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
     * @return Agencms\Core\Field
     */
    public function mode(string $mode = self::MODE_SELECT)
    {
        $this->field['mode'] = $mode;

        return $this;
    }

    /**
     * Helper method to set the select mode to checkboxes
     *
     * @return Agencms\Core\Field
     */
    public function checkbox()
    {
        return $this->mode(self::MODE_CHECKBOX);
    }

    /**
     * Helper method to set the select mode to dropdown
     *
     * @return Agencms\Core\Field
     */
    public function dropdown()
    {
        return $this->mode(self::MODE_SELECT);
    }

    /**
     * Helper method to set the select mode to tags
     *
     * @return Agencms\Core\Field
     */
    public function tags()
    {
        return $this->mode(self::MODE_TAGS);
    }

    /**
     * Helper method to set text input mode to slug
     *
     * @return Agencms\Core\Field
     */
    public function slug()
    {
        return $this->mode(self::MODE_SLUG);
    }

    /**
     * Link the output of a field to the input of another
     *
     * @param string $linkedField
     * @return Agencms\Core\Field
     */
    public function link(string $linkedField)
    {
        $this->field['link'] = $linkedField;

        return $this;
    }

    /**
     * Set the desired image ratio to be requested from the CMS. The UI will
     * automatically resize any uploaded image to match this ratio.
     *
     * @param int $width
     * @param int $height
     * @param bool $resize
     * @return Agencms\Core\Field
     */
    public function ratio(int $width, int $height, bool $resize = false)
    {
        $this->field['ratio'] = "{$width}:{$height}";

        if ($resize) {
            $this->field['imagesize'] = "{$width},{$height}";
        }

        return $this;
    }
}

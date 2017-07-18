<?php

namespace Silvanite\Agencms;

class Option
{
    protected $option;

    /**
     * Set the defaults for a new Option
     */
    protected function __construct()
    {
        $this->option = [
            'text' => 'New Option',
            'value' => 'new'
        ];
    }

    /**
     * Initialise a new option instance. This must be the first call (or a helper method).
     *
     * @param string $value
     * @param string $label
     * @return Silvanite\Agencms\Option
     */
    public static function init(string $value = null, string $label = null)
    {
        $instance = new static();

        $instance->option['value'] = $value;

        if ($label) $instance->option['text'] = $label;

        return $instance;
    }

    public function get()
    {
        return $this->option;
    }
}

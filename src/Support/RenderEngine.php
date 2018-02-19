<?php

namespace Silvanite\Agencms\Support;

class RenderEngine
{
    /**
     * Renders the repeater fields passed in to the $repeaters parameter
     *
     * @param Array|Illuminate\Support\Collection $repeaters
     * @return string
     */
    public function renderRepeater($repeaters)
    {
        return collect($repeaters)->map(function ($repeater) {
            $repeaterType = str_slug($repeater['name']);

            return view()->first(["agencms::repeaters.type.{$repeaterType}", 'agencms::repeaters.default'])
                ->with([
                    'fields' => $repeater['fields'],
                    'groups' => $repeater['groups'],
                    'content' => $repeater['content'],
                ])->render();
        })->implode('');
    }

    /**
     * Renders the field passed in to the $field parameter. Will look for key specific templates,
     * then type specific templates and finally render using the default template.
     *
     * @param Array|Illuminate\Support\Collection $repeaters
     * @return string
     */
    public function renderField($field)
    {
        $fieldType = str_slug($field['type']);
        $fieldKey = str_slug($field['key']);

        return view()->first([
            "agencms::fields.{$fieldKey}",
            "agencms::fields.type.{$fieldType}",
            'agencms::fields.default'
        ])->with(['field' => $field])->render();
    }

    public function render($value, $fieldKey = 'default', $fieldType = 'default', $params = [])
    {
        $field = array_merge([
            'type' => $fieldType,
            'key' => $fieldKey,
            'content' => $value,
        ], $params);

        return $this->renderField($field);
    }
}

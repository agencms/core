<?php

namespace Silvanite\Agencms\Traits;

/**
 * Handles the saving and retrieving of repeater fields on an eloquent model
 */
trait HasRepeaterFields
{
    /**
     * Hook into the saving event for the Model to pre-process fields for
     * maintaining compatibility with Agencms. The model must have an array
     * assigned to a $repeaters variable!!!
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            static::processRepeatersForSaving($model);
        });
    }

    /**
     * Process all repeater fields
     *
     * @param * $model
     * @return void
     */
    protected static function processRepeatersForSaving($model)
    {
        collect($model->repeaters)->map(function($item) use (&$model) {
            $model[$item] = collect($model[$item])->map(function(&$repeater) use ($item, $model) {
                $repeater['fields'] = collect($repeater['fields'])
                    ->map(function($field) use ($model, &$repeater, $item) {
                        if ($field['type'] === 'image') {
                            static::saveRepeaterImage($item, $repeater, $model, $field);
                        }

                        return $field;
                    });

                return $repeater;
            });
        });
    }

    /**
     * Save images from the repeater element and update the original request. 
     *
     * @param * $item
     * @param * $repeater
     * @param * $model
     * @param * $field
     * @return void
     */
    private static function saveRepeaterImage($item, &$repeater, $model, &$field)
    {
        /**
         * Ignore this image field if it is empty
         */
        if (!$field['content']) {
            return;
        }

        /**
         * Generate a unique image key for saving/loading
         */
        $imageKey = sprintf("%s-%s-%s", $item, $repeater['key'], $field['key']);

        $field['content'] = "{$imageKey}.medialibrary.key";

        /**
         * The CMS returns the image URL if the original image has not been
         * modified, so we don't need to do anything
         */
        if (is_string($image = $field['content'])) {
            return;
        }
        
        /**
         * Delete any existing image.
         */
        $model->getMedia('default', ['key' => $imageKey])
            ->each(function ($media, $key) {
                $media->delete();
            });

        /**
         * Because of an issue with the media library we need to save the image
         * key and then retrieve the filename after the item has been saved.
         */
        $useName = sprintf("%s.%s", 
            str_slug(pathinfo($image['name'], PATHINFO_FILENAME)),
            pathinfo($image['name'], PATHINFO_EXTENSION)
        );

        $model->addMediaFromBase64($image['image'])
        ->usingFileName($useName)
        ->usingName($imageKey)
        ->withCustomProperties([
            'key' => $imageKey,
            'mime-type' => $image['type'],
            'height' => $image['height'],
            'width' => $image['width']
        ])->toMediaCollection();
    }

    /**
     * When the model with this trait is accessed, we need to return the full
     * image Url instead of the media library key stored in the database. We do
     * this automatically by extending the default model method which returns all
     * the model's attributes.
     *
     * @return void
     */
    protected function getArrayableAttributes()
    {
        parent::getArrayableAttributes();

        foreach ($this->attributes as $key => $value) {
            if (!collect($this->repeaters)->contains($key)) {
                continue;
            };

            $this->attributes[$key] = collect(json_decode($this->attributes[$key], true))->map(function(&$repeater) {
                $repeater['fields'] = collect($repeater['fields'])
                    ->map(function($field) use (&$repeater) {
                        if ($field['type'] === 'image') {
                            $field['content'] = $this->getRepeaterImageUrl($field['content']);
                        }

                        return $field;
                    });

                return $repeater;
            });
        }

        return $this->getArrayableItems($this->attributes);
    }

    /**
     * Retrieve the full Url to an image based on the key used in the media library
     *
     * @param string $key
     * @return void
     */
    private function getRepeaterImageUrl(string $key)
    {
        if ($media = $this->getFirstMedia('default', ['key' => str_replace('.medialibrary.key', '', $key)]))
            return config('app.url') . $media->getUrl();

        return null;
    }
}

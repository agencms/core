<?php

namespace Silvanite\Agencms\Traits;

/**
 * Handles the saving and retrieving of repeater fields on an eloquent model
 */
trait HasRepeaterFields
{
    protected $attributes = null;

    /**
     * Process all repeater fields
     *
     * @param * $model
     * @return void
     */
    public function processRepeatersForSaving()
    {
        collect($this->repeaters)->map(function($item) use (&$model) {
            $this[$item] = collect($this[$item])->map(function(&$repeater) use ($item) {
                $returnAsString = false;

                if (is_string($repeater)) {
                    $repeater = (array) collect(json_decode($repeater))->first();
                }

                $repeater['fields'] = collect($repeater['fields'])
                    ->map(function($field) use (&$repeater, $item) {
                        $field = (array) $field;
                        if ($field['type'] === 'image') {
                            $this->saveRepeaterImage($item, $repeater, $field);
                        }

                        return $field;
                    });

                return $repeater;
            });
        });

        return $this;
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
    protected function saveRepeaterImage($item, &$repeater, &$field)
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

        /**
         * The CMS returns the image URL if the original image has not been
         * modified, so we need to lookup the images key and replace the filename
         * with the original key in the attributes
         */
        if (is_string($image = ((array) $field)['content'])) {
            $this->getMedia('default')
                ->each(function ($media, $key) use ($image, &$field) {
                    $mediaImage = config('app.url') . $media->getUrl();
                    if ($mediaImage === $image) {
                        $field = (array) $field;
                        $field['content'] = $media->name;
                    }
                });
            return;
        }
        
        /**
         * Delete any existing image.
         */
        $this->getMedia('default', ['key' => $imageKey])
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

        $this->addMediaFromBase64($image['image'])
        ->usingFileName($useName)
        ->usingName($imageKey)
        ->withCustomProperties([
            'key' => $imageKey,
            'mime-type' => $image['type'],
            'height' => $image['height'],
            'width' => $image['width']
        ])->toMediaCollection();

        /**
         * Save the key instead of the binary data to the database so we can
         * look it up again when retrieving the content
         */
        $field['content'] = "{$imageKey}.medialibrary.key";
    }

    /**
     * Retrieve the full Url to an image based on the key used in the media library
     *
     * @param string $key
     * @return void
     */
    private function getRepeaterImageUrl($key)
    {
        if (!is_string($key)) return null;

        if ($media = $this->getFirstMedia('default', ['key' => str_replace('.medialibrary.key', '', $key)]))
            return config('app.url') . $media->getUrl();

        return null;
    }
}

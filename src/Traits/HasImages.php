<?php

namespace Silvanite\Agencms\Traits;

use Request;

/**
 * Handles the saving and retrieving of images on models
 */
trait HasImages
{
    /**
     * Process all image field
     *
     * @return void
     */
    public function processImagesForSaving()
    {
        collect($this->images)->map(function($item) {
            $imageRequest = Request::get($item);

            if (!$imageRequest) {
                $this->deleteImage($item);
            }

            if (is_array($imageRequest) && array_key_exists('name', $imageRequest)) {
                $this->saveImage($item, $imageRequest);
            }
        });

        return $this;
    }

    /**
     * Delete an image file if it has been removed in the UI
     *
     * @param string $imageKey
     * @return void
     */
    private function deleteImage($imageKey)
    {
        $this->getMedia('default', ['key' => $imageKey])
            ->each(function ($media, $key) {
                $media->delete();
            });
    }

    /**
     * Save the image with the supplied key to be able to retrieve it later 
     *
     * @param * $item
     * @param * $image
     * @return void
     */
    private function saveImage($item, &$image)
    {
        /**
         * Ignore this image field if it is empty
         */
        if (!$image['name']) {
            return;
        }

        /**
         * Generate a unique image key for saving/loading
         */
        $imageKey = $item;

        /**
         * The CMS returns the image URL if the original image has not been
         * modified, so we don't need to do anything
         */
        if (is_string($image)) {
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
            str_slug(pathinfo(strtolower($image['name']), PATHINFO_FILENAME)),
            pathinfo(strtolower($image['name']), PATHINFO_EXTENSION)
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
    }

    /**
     * When the model with this trait is accessed, we need to return the full
     * image Url. We do this automatically by extending the default model method 
     * which returns all the model's attributes.
     *
     * @return void
     */
    protected function getArrayableAttributes()
    {
        parent::getArrayableAttributes();

        collect($this->images)->map(function($item) {
            $this->attributes[$item] = $this->getImageUrl($item);
        });

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
     * @return string
     */
    private function getImageUrl(string $key)
    {
        if ($media = $this->getFirstMedia('default', ['key' => $key]))
            return config('app.url') . $media->getUrl();

        return null;
    }
}

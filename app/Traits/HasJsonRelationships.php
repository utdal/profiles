<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Allows relation keys from nested JSON attributes
 * 
 * @author Jonas Staudenmeir
 * @link https://github.com/staudenmeir/eloquent-json-relations
 * 
 */
trait HasJsonRelationships
{
    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $attribute = preg_split('/(->|\[\])/', $key)[0];

        if (array_key_exists($attribute, $this->attributes)) {
            return $this->getAttributeValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeFromArray($key)
    {
        if (Str::contains($key, '->')) {
            return $this->getAttributeValue($key);
        }

        return parent::getAttributeFromArray($key);
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param string $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        if (Str::contains($key, '->')) {
            [$key, $path] = explode('->', $key, 2);

            if (substr($key, -2) === '[]') {
                $key = substr($key, 0, -2);

                $path = '*.' . $path;
            }

            $path = str_replace(['->', '[]'], ['.', '.*'], $path);

            return data_get($this->getAttributeValue($key), $path);
        }

        return parent::getAttributeValue($key);
    }
}
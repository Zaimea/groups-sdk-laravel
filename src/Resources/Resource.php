<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

use Zaimea\SDK\Groups\SDK;

#[\AllowDynamicProperties]
class Resource
{
    /**
     * The resource attributes.
     *
     * @var array
     */
    public array $attributes = [];

    /**
     * The Group SDK instance.
     *
     * @var \Zaimea\SDK\Groups\SDK|null
     */
    protected ?SDK $sdk = null;

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct(array $attributes, ?SDK $sdk = null)
    {
        $this->attributes = $attributes;
        $this->sdk = $sdk;

        $this->fill();
    }

    /**
     * Fill the resource with the array of attributes.
     *
     * @return void
     */
    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $key = $this->camelCase($key);

            $this->{$key} = $value;
        }
    }

    /**
     * Convert the key name to camel case.
     *
     * @param  string  $key
     * @return string
     */
    protected function camelCase($key)
    {
        $parts = explode('_', $key);

        foreach ($parts as $i => $part) {
            if ($i !== 0) {
                $parts[$i] = ucfirst($part);
            }
        }

        return str_replace(' ', '', implode(' ', $parts));
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  string  $class
     * @return array
     */
    protected function transformCollection(array $collection, $class, array $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this->sdk);
        }, $collection);
    }

    /**
     * Transform a paginated collection to the given class.
     *
     * @param  array  $response
     * @param  string  $class
     * @param  array  $extraData 
     * @return array
     */
    protected function transformCollectionPaginate($response, $class, $extraData = [])
    {
        $transformedData = [];
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $item) {
                $transformedData[] = new $class($item + $extraData, $this);
            }
        }

        return [
            'data' => $transformedData,
            'links' => $response['links'] ?? [
                'first' => null,
                'last' => null,
                'prev' => null,
                'next' => null,
            ],
            'meta' => $response['meta'] ?? [
                'path' => null,
                'per_page' => count($transformedData),
                'next_cursor' => null,
                'prev_cursor' => null,
            ],
            'included' => $response['included'] ?? [],
        ];
    }

    /**
     * Transform the collection of tags to a string.
     *
     * @param  string|null  $separator
     * @return string
     */
    protected function transformTags(array $tags, $separator = null)
    {
        $separator = $separator ?: ', ';

        return implode($separator, array_column($tags ?? [], 'name'));
    }
}
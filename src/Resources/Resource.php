<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

use Illuminate\Pagination\AbstractPaginator;
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
    protected function fill(): void
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
    protected function camelCase($key): string
    {
        $key = (string) $key;
        
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
     * @param  array  $collection
     * @param  string  $class
     * @param  array  $extraData
     * @return array|AbstractPaginator
     */
    protected function transformCollection(array $collection, string $class, array $extraData = []): array|AbstractPaginator
    {
        if (is_array($collection) && isset($collection['data']) && isset($collection['meta'])) {
            $collection['data'] = array_map(function ($data) use ($class, $extraData) {
                return new $class($data + $extraData, $this);
            }, $collection['data']);
            
            return $collection;
        }
        
        if ($collection instanceof AbstractPaginator) {
            $collection->getCollection()->transform(function ($data) use ($class, $extraData) {
                return new $class($data + $extraData, $this);
            });
            
            return $collection;
        }
        
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Transform the collection of tags to a string.
     *
     * @param  array  $tags
     * @param  string|null  $separator
     * @return string
     */
    protected function transformTags(array $tags, $separator = null): string
    {
        $separator = $separator ?: ', ';

        return implode($separator, array_column($tags ?? [], 'name'));
    }
}
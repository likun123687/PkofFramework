<?php

namespace Pkof\Services\Cache;

/**
 * Class Cache
 * @package Pkof\Services\Cache
 */
class Cache implements StoreInterface
{
    private $store;

    /**
     * Cache constructor.
     *
     * @param StoreInterface $store
     */
    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * @param $key
     */
    public function get($key)
    {
        $this->store->get($key);
    }

    /**
     * @param array $keys
     */
    public function many(array $keys)
    {
        $this->store->many($keys);
    }

    /**
     * @param $key
     * @param $value
     * @param $minutes
     */
    public function put($key, $value, $minutes)
    {
        $this->store->put($key, $value, $minutes);
    }

    /**
     * @param     $key
     * @param int $value
     */
    public function increment($key, $value = 1)
    {
        $this->store->increment($key, $value);
    }

    /**
     * @param     $key
     * @param int $value
     */
    public function decrement($key, $value = 1)
    {
        $this->store->decrement($key, $value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function forever($key, $value)
    {
        $this->store->forever($key, $value);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        $this->store->forget($key);
    }

    /**
     * Flush cache data
     */
    public function flush()
    {
        $this->store->flush();
    }

    /**
     * Get cache prefix
     */
    public function getPrefix()
    {
        $this->store->getPrefix();
    }

    /**
     * Set prefix
     *
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->setPrefix($prefix);
    }
}

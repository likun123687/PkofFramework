<?php

namespace Pkof\Services\Cache;

/**
 * Class Cache
 * @author likun
 */
class Cache implements StoreInterface
{
    private $store;

    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    public function get($key)
    {
        $this->store->get($key);
    }

    public function many(array $keys)
    {
        $this->store->many($keys);
    }

    public function put($key, $value, $minutes)
    {
        $this->store->put($key, $value, $minutes);
    }

    public function increment($key, $value = 1)
    {
        $this->store->increment($key, $value);
    }

    public function decrement($key, $value = 1)
    {
        $this->store->decrement($key, $value);
    }

    public function forever($key, $value)
    {
        $this->store->forever($key, $value);
    }

    public function forget($key)
    {
        $this->store->forget($key);
    }

    public function flush()
    {
        $this->store->flush();
    }

    public function getPrefix()
    {
        $this->store->getPrefix();
    }

    public function setPrefix($prefix)
    {
        $this->setPrefix($prefix);
    }
}

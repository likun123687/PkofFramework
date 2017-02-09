<?php

namespace Pkof\Services\Cache;

/**
 * Class MemcachedStore
 * @author likun
 */
class MemcachedStore
{
    private $client;
    private $prefix;

    public function __construct(\Memcached $client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    public function get($key)
    {
        $this->client->get($key);
    }

    public function many(array $keys)
    {
        return $this->client->getMulti($keys);
    }

    public function put($key, $value, $minutes = 24 * 60)
    {
        $this->client->set($key, $value, $minutes * 60);
    }

    public function increment($key, $value = 1)
    {
        return $this->client->increment($key, $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->client->decrement($key, $value);
    }

    public function forever($key, $value)
    {
        $this->client->set($key, $value);
    }

    public function forget($key)
    {
        $this->client->delete($key);
    }

    public function flush()
    {
        $this->client->flush();
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}

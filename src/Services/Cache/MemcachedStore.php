<?php

namespace Pkof\Services\Cache;

/**
 * Class MemcachedStore
 * @author likun
 */
class MemcachedStore implements StoreInterface
{
    private $client;
    private $prefix;

    /**
     * MemcachedStore constructor.
     * @param \Memcached $client
     * @param $prefix
     */
    public function __construct(\Memcached $client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    private function getPrefixKey($key)
    {
        if (is_string($key)) {
            return $this->prefix . $key;
        } elseif (is_array($key)) {
            return array_walk($key, function (&$key, $itemKey, $value) {

            });
        }
        throw new \InvalidArgumentException("key type error" . var_export($key, true));
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * @param array $keys
     * @return mixed
     */
    public function many(array $keys)
    {
        if (empty($keys)) {
            throw new \InvalidArgumentException('Can not get multi keys by empty array: ' . var_export($keys, true));
        }
        return $this->client->getMulti($keys);
    }

    /**
     * @param $key
     * @param $value
     * @param int $minutes
     */
    public function put($key, $value, $minutes = 24 * 60)
    {
        if (false == $this->client->set($key, $value, $minutes * 60)) {
            throw new \RuntimeException('Setex cache error: ' . print_r(func_get_args(), true) . $this->client->getResultMessage());
        }
    }


    /**
     * @param $key
     * @param int $value
     * @return int
     */
    public function increment($key, $value = 1)
    {
        $result = $this->client->increment($key, $value);
        if (false == $result) {
            throw new \RuntimeException('increment key error: ' . print_r(func_get_args(), true) . $this->client->getResultMessage());
        }
        return $result;
    }

    /**
     * @param $key
     * @param int $value
     * @return int
     */
    public function decrement($key, $value = 1)
    {
        $result = $this->client->decrement($key, $value);
        if (false == $result) {
            throw new \RuntimeException('Decrement key error: ' . print_r(func_get_args(), true) . $this->client->getResultMessage());
        }
        return $result;
    }

    /**
     * @param $key
     * @param $value
     */
    public function forever($key, $value)
    {
        if (false == $this->client->set($key, $value)) {
            throw new \RuntimeException('for forever cache error: ' . print_r(func_get_args(), true) . $this->client->getResultMessage());
        }
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        if (false == $this->client->delete($key)) {
            throw new \RuntimeException('delete key error: ' . print_r(func_get_args(), true) . $this->client->getResultMessage());
        }
    }

    /**
     * Flush cache
     */
    public function flush()
    {
        if (false == $this->client->flush()) {
            throw new \RuntimeException('flush cache error: ' . $this->client->getResultMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}

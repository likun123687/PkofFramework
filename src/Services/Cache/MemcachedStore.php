<?php

namespace Pkof\Services\Cache;

use Pkof\Exceptions\Error\InvalidArgumentWithContextException;
use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class MemcachedStore
 * @author likun
 */
class MemcachedStore
{
    private $client;
    private $prefix;

    /**
     * MemcachedStore constructor.
     *
     * @param \Memcached $client
     * @param            $prefix
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
            return array_walk($key, function (&$key, $key, $prefix) {

            });
        }
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * @param array $keys
     *
     * @return mixed
     */
    public function many(array $keys)
    {
        if (empty($keys)) {
            throw new InvalidArgumentWithContextException('Can not get multi keys by empty array', $keys);
        }

        return $this->client->getMulti($keys);
    }

    /**
     * @param     $key
     * @param     $value
     * @param int $minutes
     */
    public function put($key, $value, $minutes = 24 * 60)
    {
        if (false == $this->client->set($key, $value, $minutes * 60)) {
            throw new RuntimeWithContextException('Setex cache error: ' . $this->client->getResultMessage(),
                func_get_args());
        }
    }


    /**
     * @param     $key
     * @param int $value
     *
     * @return int
     */
    public function increment($key, $value = 1)
    {
        $result = $this->client->increment($key, $value);
        if (false == $result) {
            throw new RuntimeWithContextException('Increment key error: ' . $this->client->getResultMessage(), func_get_args());
        }

        return $result;
    }

    /**
     * @param     $key
     * @param int $value
     *
     * @return int
     */
    public function decrement($key, $value = 1)
    {
        $result = $this->client->decrement($key, $value);
        if (false == $result) {
            throw new RuntimeWithContextException('Decrement key error: ' . $this->client->getResultMessage(), func_get_args());
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
            throw new RuntimeWithContextException('Forever cache error: ' . $this->client->getResultMessage(), func_get_args());
        }
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        if (false == $this->client->delete($key)) {
            throw new RuntimeWithContextException('Delete key error: ' . $this->client->getResultMessage(), func_get_args());
        }
    }

    /**
     * Flush cache
     */
    public function flush()
    {
        if (false == $this->client->flush()) {
            throw new RuntimeWithContextException('flush cache error: ' . $this->client->getResultMessage());
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

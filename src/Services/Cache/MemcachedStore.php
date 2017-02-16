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
    use PrefixTrait;
    private $client;

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

    /**
     * @param $key
     *
     * @return array|string
     */


    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->client->get($this->getPrefixKey($key));
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

        return $this->client->getMulti($this->getPrefixKey($keys));
    }

    /**
     * @param     $key
     * @param     $value
     * @param int $minutes
     */
    public function put($key, $value, $minutes = 24 * 60)
    {
        $key = $this->getPrefixKey($key);
        if (false == $this->client->set($key, $value, $minutes * 60)) {
            throw new RuntimeWithContextException('Setex cache error: ' . $this->client->getResultMessage(), func_get_args());
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
        $key    = $this->getPrefixKey($key);
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
        $key    = $this->getPrefixKey($key);
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
        $key = $this->getPrefixKey($key);
        if (false == $this->client->set($key, $value)) {
            throw new RuntimeWithContextException('Forever cache error: ' . $this->client->getResultMessage(), func_get_args());
        }
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        $key = $this->getPrefixKey($key);
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
}

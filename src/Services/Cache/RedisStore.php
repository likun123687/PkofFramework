<?php

namespace Pkof\Services\Cache;

use Predis\Client;

/**
 * Class RedisStore
 * @author likun
 */
class RedisStore implements StoreInterface
{
    private $client;
    private $prefix;

    /**
     * RedisStore constructor.
     * @param Client $client
     * @param $prefix
     */
    public function __construct(Client $client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * @param $key
     * @return string
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
            throw new \InvalidArgumentException('Can not get multi keys by empty array: '. var_export($keys, true));
        }

        return call_user_func_array(array($this->client, 'mget'), $keys);
    }

    /**
     * @param $key
     * @param $value
     * @param int $minutes
     */
    public function put($key, $value, $minutes = 24 * 60)
    {
        if ($this->client->setex($key, $value, $minutes * 60) !== 'OK') {
            throw new \RuntimeException('Setex cache error: ' . print_r(func_get_args(),true));
        }
    }

    /**
     * @param $key
     * @param int $value
     * @return string
     */
    public function increment($key, $value = 1)
    {
        return $this->client->incrbyfloat($key, $value);
    }

    /**
     * @param $key
     * @param int $value
     * @return string
     */
    public function decrement($key, $value = 1)
    {
        return $this->client->incrbyfloat($key, -$value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function forever($key, $value)
    {
        if ($this->client->set($key, $value) !== 'OK') {
            throw new \RuntimeException('set key cache forever error: ' . print_r(func_get_args(),true));
        }
    }

    /**
     * @param $key
     * @return int
     */
    public function forget($key)
    {
        return $this->client->del($key);
    }

    /**
     * flush cache
     */
    public function flush()
    {
        if ($this->client->flushdb() !== 'OK') {
            throw new \RuntimeException("Flush cache error");
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

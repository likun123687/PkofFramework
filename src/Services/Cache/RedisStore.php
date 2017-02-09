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

    public function __construct(Client $client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function many(array $keys)
    {
        if (empty($keys)) {
            return [];
        }

        return call_user_func_array(array($this->client, 'mget'), $keys);
    }

    public function put($key, $value, $minutes = 24 * 60)
    {
        if ($this->client->setex($key, $value, $minutes * 60) !== 'ok') {
            throw new \Exception('setex error');
        }
    }

    public function increment($key, $value = 1)
    {
        return $this->client->incrbyfloat($key, $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->client->incrbyfloat($key, -$value);
    }

    public function forever($key, $value)
    {
        if ($this->client->set($key, $value) !== 'OK') {
            throw Exception("set key: $key, value: $value");
        }
    }

    public function forget($key)
    {
        return $this->client->del($key);
    }

    public function flush()
    {
        if ($this->client->flushdb() !== 'OK') {
            throw Exception("Flush db error");
        }
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

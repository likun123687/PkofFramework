<?php

namespace Pkof\Services\Cache;

/**
 * Class StoreInterface
 * @author likun
 */
Interface StoreInterface
{
    public function get($key);

    public function many(array $keys);

    public function put($key, $value, $minutes);

    public function increment($key, $value = 1);

    public function decrement($key, $value = 1);

    public function forever($key, $value);

    public function forget($key);

    public function flush();

    public function getPrefix();

    public function setPrefix($prefix);
}

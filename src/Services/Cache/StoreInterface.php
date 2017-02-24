<?php

namespace Pkof\Services\Cache;

/**
 * Class StoreInterface
 * @author likun
 */
Interface StoreInterface
{
    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param array $keys
     *
     * @return mixed
     */
    public function many(array $keys);

    /**
     * @param $key
     * @param $value
     * @param $minutes
     *
     * @return mixed
     */
    public function put($key, $value, $minutes);

    /**
     * @param     $key
     * @param int $value
     *
     * @return mixed
     */
    public function increment($key, $value = 1);

    /**
     * @param     $key
     * @param int $value
     *
     * @return mixed
     */
    public function decrement($key, $value = 1);

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function forever($key, $value);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function forget($key);

    /**
     * @return mixed
     */
    public function flush();
}

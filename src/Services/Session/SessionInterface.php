<?php

namespace Pkof\Services\Session;

/**
 * Interface SessionInterface
 * @package Pkof\Services\Session
 */
interface SessionInterface
{
    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = NULL);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $name
     *
     * @return mixed
     */
    public function has($name);

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function put($key, $value);

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function pull($key, $default = NULL);

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

    /**
     * @return mixed
     */
    public function regenerate();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param $name
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function start();

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function flash($key, $value);
}

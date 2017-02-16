<?php

namespace Pkof\Services\Session;

/**
 * Interface SessionInterface
 * @package Pkof\Services\Session
 */
interface SessionInterface
{
    public function get($key, $default = NULL);

    public function all();

    public function has($name);

    public function put($key, $value);

    public function pull($key, $default = NULL);

    public function forget($key);

    public function flush();

    public function regenerate();

    public function getId();

    public function setId($id);

    public function getName();

    public function setName($name);

    public function start();

    public function flash($key, $value);
}

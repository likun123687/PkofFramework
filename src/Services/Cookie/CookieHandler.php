<?php

namespace Pkof\Services\Cookie;

use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class CookieHandler
 * @package Pkof\Services\Cookie
 */
class CookieHandler
{
    private $adapter;

    public function __construct(CookieAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param        $name
     * @param string $default
     *
     * @return string
     */
    public function get($name, $default = '')
    {
        return isset($this->adapter[$name]) ? $this->adapter[$name] : $default;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->adapter[$name]);
    }

    /**
     * @param        $name
     * @param string $value
     * @param int    $minutes
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function set($name, $value = '', $minutes = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        if (false == setcookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)) {
            throw new RuntimeWithContextException('Set cookie error.', func_get_args());
        }
    }

    /**
     * @param $name
     */
    public function delete($name)
    {
        unset($this->adapter[$name]);
        setcookie($name, "", time() - 3600);
    }

    /**
     * @param        $name
     * @param string $value
     * @param int    $minutes
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function update($name, $value = '', $minutes = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        if (false == setcookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)) {
            throw new RuntimeWithContextException('Update cookie error.', func_get_args());
        }
    }
}

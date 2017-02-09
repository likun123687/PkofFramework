<?php

namespace Pkof\Services\Cookie;

/**
 * Class CookieHandler
 * @author likun
 */
class CookieHandler
{
    public function get($name, $default = '')
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    public function has($name)
    {
        return isset($_COOKIE[$name]);
    }

    public function set($name, $value = '', $minutes = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        if (false == setcookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)) {
            throw new \Exception('Set cookie error');
        }
    }

    public function delete($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, "", time() - 3600);
    }

    public function update($name, $value = '', $minutes = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        if (false == setcookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)) {
            throw new \Exception('Set cookie error');
        }
    }
}

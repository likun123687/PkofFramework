<?php

namespace Pkof\Services\Session;

/**
 * Class Session
 * @author likun
 */

class Session implements SessionInterface
{
    private $flashKeyName;

    public function __construct($flashKeyName)
    {
        $this->flashKeyName = $flashKeyName;
    }

    public function get($key, $default = NULL)
    {
        $value = isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
        if (isset($_SESSION[$this->flashKeyName][$key])) {
            unset($_SESSION[$key]);
        }

        return $value;
    }

    public function all()
    {
        return $_SESSION;
    }

    public function has($name)
    {
        return isset($_SESSION[$name]);
    }

    public function put($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function pull($name, $default = NULL)
    {
        $result = isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
        unset($_SESSION[$name]);

        return $result;
    }

    public function forget($name)
    {
        unset($_SESSION[$name]);
    }

    public function flush()
    {
        session_unset();
    }

    public function regenerate($delete_old_session = false)
    {
        return session_regenerate_id($delete_old_session);
    }

    public function getId()
    {
        return session_id();
    }

    public function setId($id)
    {
        return session_id($id);
    }

    public function getName()
    {
        return session_name();
    }

    public function setName($name)
    {
        return session_name($name);
    }

    public function start()
    {
        return session_start();
    }

    public function destroy()
    {
        return session_destroy();
    }

    public function flash($key, $value)
    {
        $_SESSION[$key] = $value;

        if (!isset($_SESSION[$this->flashKeyName])) {
            $_SESSION[$this->flashKeyName] = [];
        }

        $_SESSION[$this->flashKeyName][] = $key;
    }
}

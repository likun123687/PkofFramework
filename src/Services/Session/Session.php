<?php

namespace Pkof\Services\Session;

/**
 * Class Session
 * @package Pkof\Services\Session
 */
class Session implements SessionInterface
{
    private $flashKeyName;
    private $sessionAdapter;

    /**
     * Session constructor.
     *
     * @param SessionAdapter $sessionAdapter
     * @param                $flashKeyName
     */
    public function __construct(SessionAdapter $sessionAdapter, $flashKeyName)
    {
        $this->sessionAdapter = $sessionAdapter;
        $this->flashKeyName   = $flashKeyName;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = NULL)
    {
        $value = isset($this->sessionAdapter[$key]) ? $this->sessionAdapter[$key] : $default;
        if ($this->sessionAdapter[$this->flashKeyName][$key]) {
            unset($this->sessionAdapter[$key]);
        }

        return $value;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->sessionAdapter->toArray();
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->sessionAdapter[$name]);
    }

    /**
     * @param $name
     * @param $value
     */
    public function put($name, $value)
    {
        $this->sessionAdapter[$name] = $value;
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function pull($name, $default = NULL)
    {
        $result = isset($this->sessionAdapter[$name]) ? $this->sessionAdapter[$name] : $default;
        unset($this->sessionAdapter[$name]);

        return $result;
    }

    /**
     * @param $name
     */
    public function forget($name)
    {
        unset($this->sessionAdapter[$name]);
    }

    /**
     * @param $key
     * @param $value
     */
    public function flash($key, $value)
    {
        $this->sessionAdapter[$key] = $value;

        if (!isset($this->sessionAdapter[$this->flashKeyName])) {
            $this->sessionAdapter[$this->flashKeyName] = [];
        }

        $this->sessionAdapter[$this->flashKeyName][] = $key;
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
}

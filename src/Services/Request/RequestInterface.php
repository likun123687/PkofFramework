<?php
namespace Pkof\Services\Request;

/**
 * Interface RequestInterface
 * @author likun
 */
interface RequestInterface
{
    public function path();

    public function url();

    public function method();

    public function isMethod($method);

    public function input($name, $default = NULL);

    public function has($name);

    public function all();
}

?>

<?php
namespace Pkof;

use Pimple\Container;

class App
{
    private $version = "1.0";
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __callStatic($name, $arguments)
    {

    }

    public function registerService($service)
    {

    }

}
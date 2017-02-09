<?php
namespace Pkof\View;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/7
 * Time: PM8:52
 */
class View
{
    private $viewPathRoot;

    private $data = [];
    private $path;

    public function __construct($viewPathRoot)
    {
        $this->viewPathRoot = $viewPathRoot;
    }

    public function with(array $data)
    {

    }

    public function path($path)
    {

    }

    public function render()
    {

    }
}
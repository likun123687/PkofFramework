<?php
namespace Pkof\Services\View;
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

    /**
     * View constructor.
     *
     * @param $viewPathRoot
     */
    public function __construct($viewPathRoot)
    {
        $this->viewPathRoot = $viewPathRoot;
    }

    /**
     * @param array $data
     */
    public function with(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $path
     */
    public function path($path)
    {
        $this->path = $path;
    }

    /**
     * render the view file
     */
    public function render()
    {
        extract($this->data);
        require $this->viewPathRoot . '/' . $this->path;
    }
}
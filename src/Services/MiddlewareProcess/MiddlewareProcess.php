<?php

namespace Pkof\Services\MiddlewareProcess;

use Pkof\Services\Request\Request;
use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class MiddlewareProcess
 * @package Pkof\Services\MiddlewareProcess
 */
class MiddlewareProcess
{
    private $beforeMiddlewareArr = [];
    private $afterMiddlewareArr  = [];

    private $request;

    /**
     * MiddlewareProcess constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $router
     * @param $middleware
     */
    public function before($router, $middleware)
    {
        $this->register($router, $middleware, 'before');
    }


    /**
     * @param $router
     * @param $middleware
     */
    public function after($router, $middleware)
    {
        $this->register($router, $middleware, 'after');
    }

    /**
     * @param        $router
     * @param        $middleware
     * @param string $when
     */
    private function register($router, $middleware, $when = 'before')
    {
        if ($when == 'before') {
            $this->beforeMiddlewareArr[$router] = $middleware;
        } elseif ($when == 'after') {
            $this->afterMiddlewareArr[$router] = $middleware;
        }
    }

    /**
     * @param $middleware
     * @param $callParams
     */
    private function handle($middleware, $callParams)
    {
        $reflect  = new \ReflectionClass($middleware);
        $callFunc = $reflect->getMethod('handle');
        $params   = $callFunc->getParameters();

        if (count($params) > 0 && $params[0] instanceof Request) {
            array_unshift($callParams, $this->request);
        }
        call_user_func_array($callFunc, $callParams);
    }

    /**
     * @param $url
     * @param $callParams
     */
    public function beforeMiddleHandle($url, $callParams)
    {
        if (empty($this->beforeMiddlewareArr[$url])) {
            throw new RuntimeWithContextException('Not found url middleware.', $url);
        }

        $middleware = $this->beforeMiddlewareArr[$url];
        $this->handle($middleware, $callParams);
    }

    /**
     * After middleware array
     *
     * @param
     *
     * @return
     */
    public function afterMiddleHandle($url, $callParams)
    {
        if (empty($this->afterMiddlewareArr[$url])) {
            throw new RuntimeWithContextException('Not found url middleware.', $url);
        }

        $middleware = $this->afterMiddlewareArr[$url];
        $this->handle($middleware, $callParams);
    }

}

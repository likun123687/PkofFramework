<?php

namespace Pkof\Services\MiddlewareProcess;

use Pkof\Services\Request\Request;

/**
 * Class MiddlewareProcess
 * @author yourname
 */
class MiddlewareProcess
{
    private $beforeMiddlewareArr = [];
    private $afterMiddlewareArr  = [];

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function before($router, $middleware)
    {
        $this->register($router, $middleware, 'before');
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function after($router, $middleware)
    {
        $this->register($router, $middleware, 'after');
    }

    private function register($router, $middleware, $when = 'before')
    {
        if ($when == 'before') {
            $this->beforeMiddlewareArr[$router] = $middleware;
        } elseif ($when == 'after') {
            $this->afterMiddlewareArr[$router] = $middleware;
        }
    }

    private function handle($middleware, $call_params)
    {
        $reflect   = new \ReflectionClass($middleware);
        $call_func = $reflect->getMethod('handle');
        $params    = $call_func->getParameters();

        if (count($params) > 0 && $params[0] instanceof Request) {
            array_unshift($call_params, $this->request);
        }
        call_user_func_array($call_func, $call_params);
    }

    public function beforeMiddleHandle($url, $callParams)
    {
        if (empty($this->beforeMiddlewareArr[$url])) {
            throw new Exception('not found url middleware');
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
            throw new Exception('not found url middleware');
        }

        $middleware = $this->afterMiddlewareArr[$url];
        $this->handle($middleware, $callParams);
    }

}

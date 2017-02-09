<?php
namespace Pkof\Services\ErrorHandler;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/7
 * Time: AM4:47
 */
class ErrorHandler
{
    private $request;
    private $response;
    private $except = [];

    private $exception;

    public function __construct($Request, $Response)
    {
        $this->request  = $Request;
        $this->response = $Response;
    }

    public function setException(Exception $e)
    {
        $this->exception = $e;
    }

    public function handler()
    {
        if ($this->exception instanceof RuntimeException) {

        }
    }
}
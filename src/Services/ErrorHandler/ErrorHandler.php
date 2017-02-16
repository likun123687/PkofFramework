<?php
namespace Pkof\Services\ErrorHandler;

use Pkof\Services\Request\Request;
use Pkof\Services\Response\Response;
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

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function setException(\Exception $e)
    {
        $this->exception = $e;
    }

    public function handler()
    {
        //if http exception
        //if validator exception
        //if other exception
    }
}
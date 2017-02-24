<?php
namespace Pkof\Services\ErrorHandler;

use Pkof\Services\Request\Request;
use Pkof\Services\Response\Response;

/**
 * Class ErrorHandler
 * @package Pkof\Services\ErrorHandler
 */
class ErrorHandler implements ErrorHandlerInterface
{
    private $request;
    private $response;
    private $except = [];

    private $exception;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function setException(\Exception $e)
    {
        $this->exception = $e;
    }

    public function handle()
    {
        if (!($this->exception instanceof \Exception)) {
            echo "xxxx";
            return;
        }
        $exceptionType = get_class($this->exception);
        if ($exceptionType == 'Pkof\Exceptions\Http\HttpException') {
            $this->response->setStatus($this->exception->getCode());
            $this->response->setContent('');
        }
// elseif () {
//
//        }
        //if http exception
        //if validator exception
        //if other exception
    }
}
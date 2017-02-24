<?php
namespace Pkof\Services\Validator\ValidatorException;
/**
 * Class ValidatorException
 * @package Pkof\Services\Validator\ValidatorException
 */
class ValidatorException extends \Exception
{
    private $errorMsg;

    public function __construct($errorMsg)
    {
        $this->errorMsg = $errorMsg;
        parent::__construct();
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
}
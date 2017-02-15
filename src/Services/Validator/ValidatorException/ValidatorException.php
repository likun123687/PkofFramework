<?php

/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/15
 * Time: AM4:43
 */
class ValidatorException extends \Exception
{
    private $errorMsg;

    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
}
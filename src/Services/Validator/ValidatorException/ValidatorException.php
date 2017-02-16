<?php
namespace Pkof\Services\Validator\ValidatorException;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/15
 * Time: AM4:43
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
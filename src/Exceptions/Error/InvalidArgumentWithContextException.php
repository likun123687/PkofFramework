<?php
namespace Pkof\Exceptions\Error;

/**
 * Class InvalidArgumentWithContextException
 * @package Pkof\Exceptions\Error
 */
class InvalidArgumentWithContextException extends \InvalidArgumentException
{
    /**
     * InvalidArgumentWithContextException constructor.
     *
     * @param string $message
     * @param null   $context
     */
    public function __construct($message, $context = NULL)
    {
        if ($context) {
            $message = $message . ' context:' . var_export($context, true);
        }
        parent::__construct($message);
    }
}
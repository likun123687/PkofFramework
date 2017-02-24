<?php
namespace Pkof\Exceptions\Error;

/**
 * Class RuntimeWithContextException
 * @package Pkof\Exceptions\Error
 */
class RuntimeWithContextException extends \RuntimeException
{
    /**
     * RuntimeWithContextException constructor.
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
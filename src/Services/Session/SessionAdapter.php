<?php
namespace Pkof\Services\Session;

use Pkof\Utils\RecursiveArrayAccess;

/**
 * Class SessionAdapter
 * @package Pkof\Services\Session
 */
class SessionAdapter extends RecursiveArrayAccess
{
    public function __construct(array $data, \SessionHandlerInterface $sessionHandler)
    {
        session_set_save_handler($sessionHandler);
        parent::__construct($data);
    }
}
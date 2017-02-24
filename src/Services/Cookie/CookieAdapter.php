<?php
namespace Pkof\Services\Cookie;

use Pkof\Utils\RecursiveArrayAccess;

/**
 * Class CookieAdapter
 * @package Pkof\Services\Cookie
 */
class CookieAdapter extends RecursiveArrayAccess
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
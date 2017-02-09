<?php
namespace Pkof\Services\Response;

/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/5
 * Time: AM3:49
 */
interface ResponseInterface
{
    public function getStatus();

    public function setStatus($status);

    public function getContent();

    public function setContent($content);

    public function header($key, $values);

    public function withHeaders(array $headers);

    public function json(array $data);

    public function render();

}
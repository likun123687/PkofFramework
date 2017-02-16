<?php
namespace Pkof\Services\Response;

/**
 * Interface ResponseInterface
 * @package Pkof\Services\Response
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
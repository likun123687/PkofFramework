<?php
namespace Pkof\Services\Response;

/**
 * Interface ResponseInterface
 * @package Pkof\Services\Response
 */
interface ResponseInterface
{
    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param $status
     *
     * @return mixed
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param $content
     *
     * @return mixed
     */
    public function setContent($content);

    /**
     * @param $key
     * @param $values
     *
     * @return mixed
     */
    public function header($key, $values);

    /**
     * @param array $headers
     *
     * @return mixed
     */
    public function withHeaders(array $headers);

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function json(array $data);

    /**
     * @return mixed
     */
    public function render();
}
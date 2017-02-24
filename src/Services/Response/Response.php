<?php

namespace Pkof\Services\Response;

use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class Response
 * @package Pkof\Services\Response
 */
class Response implements ResponseInterface
{
    const CONTENT_TYPE_HEADER_NAME = 'Content-type';
    private $contentType;

    private $status;
    private $content;
    private $headers = [];

    /**
     * Response constructor.
     *
     * @param ContentType $contentType
     */
    public function __construct(ContentType $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param $key
     * @param $values
     */
    public function header($key, $values)
    {
        $this->headers[$key] = $values;
        //check content type
        if ($key == self::CONTENT_TYPE_HEADER_NAME) {
            $this->contentType->checkContentType($values);
        }
    }

    /**
     * @param array $headers
     */
    public function withHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
            //check content type
            if ($key == self::CONTENT_TYPE_HEADER_NAME) {
                $this->contentType->checkContentType($value);
            }
        }
    }

    /**
     * @param array $data
     */
    public function json(array $data)
    {
        $this->contentType->checkContentType('application/json');
        $this->content = $data;
    }

    /**
     * @return string
     */
    private function toJson()
    {
        $jsonBody = json_encode($this->content);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Unable to parse JSON: ' . json_last_error());
        }

        return $jsonBody;
    }

    /**
     * Render content
     */
    public function render()
    {
        //set response code
        http_response_code($this->status);

        //set header
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        switch ($this->contentType->getContentType()) {
            case 'utf-8':
                echo $this->toJson();
                break;
            case 'text/html':
                echo $this->content;
                break;
        }

        throw new RuntimeWithContextException('Can not render content type', $this->contentType->getContentType());
    }

}
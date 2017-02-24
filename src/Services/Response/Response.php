<?php

namespace Pkof\Services\Response;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/5
 * Time: AM3:49
 */
class Response implements ResponseInterface
{
    const CONTENT_TYPE_HEADER_NAME = 'Content-type';
    private $contentType;

    private $status;
    private $content;
    private $headers = [];

    public function __construct(ContentType $contentType)
    {
        $this->contentType = $contentType;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function header($key, $values)
    {
        $this->headers[$key] = $values;
        //check content type
        if ($key == self::CONTENT_TYPE_HEADER_NAME) {
            $this->contentType->checkContentType($values);
        }
    }

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

    public function json(array $data)
    {
        $this->contentType->checkContentType('application/json');
        $this->content = $data;
    }

    private function toJson()
    {
        $jsonBody = json_encode($this->content);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Unable to parse JSON: ' . json_last_error());
        }

        return $jsonBody;
    }

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

        throw new \RuntimeException('Can not render content type: ' . var_export($this->contentType->getContentType(), true));
    }

}
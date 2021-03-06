<?php
namespace Pkof\Services\Response;

use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class ContentType
 * @package Pkof\Services\Response
 */
class ContentType
{
    const CONTENT_TYPE_ARR = [
        'text/html',
        'application/json'
    ];

    const CHARSET_ARR = [
        'utf-8',
    ];

    private $contentType = 'text/html';
    private $charset     = 'utf-8';

    /**
     * @param $contentType
     */
    public function checkContentType($contentType)
    {
        $contentTypeArr = explode(';', $contentType);
        if (count($contentTypeArr) == 1) {
            if (!in_array($contentTypeArr[0], self::CONTENT_TYPE_ARR)) {
                throw new RuntimeWithContextException('Not found content type', $contentType);
            }

            $this->contentType = $contentTypeArr[0];
        } elseif (count($contentTypeArr) == 2) {
            if (!in_array($contentTypeArr[0], self::CONTENT_TYPE_ARR)) {
                throw new RuntimeWithContextException('Not found content type', $contentTypeArr);
            }

            if (!in_array($contentTypeArr[1], self::CHARSET_ARR)) {
                throw new RuntimeWithContextException('Not found charset', $contentTypeArr);
            }

            $this->contentType = $contentTypeArr[0];
            $this->charset     = $contentTypeArr[1];
        }
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }
}
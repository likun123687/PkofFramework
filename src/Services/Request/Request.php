<?php

namespace Pkof\Services\Request;

use Pkof\Services\Cookie\CookieHandler;
use Pkof\Services\Session\Session;

/**
 * Class Request
 * @author likun
 */
class Request implements RequestInterface
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT    = 'PUT';
    const METHOD_HEAD   = 'HEAD';

    const CONTENT_TYPE_HTML                  = '';
    const CONTENT_TYPE_JSON                  = '';
    const CONTENT_TYPE_X_WWW_FORM_URLENCODED = '';
    const CONTENT_TYPE_MULTIPART_FORM_DATA   = '';

    private $method;
    private $userAgent;
    private $remoteIp;
    private $remotePort;
    private $contentType;
    private $isKeepAlive;
    private $body;
    private $inputParamsArr = [];

    private $scheme;
    private $user;
    private $password;
    private $host;
    private $port;
    private $path;
    private $query;
    private $url;

    private $cookieHandler;
    private $session;

    public function __construct(CookieHandler $cookieHandler, Session $session)
    {
        $this->cookieHandler = $cookieHandler;
        $this->session       = $session;
    }

    private function parseContentFromBody()
    {
        if ($this->contentType == self::CONTENT_TYPE_JSON) {
            $this->inputParamsArr = json_decode(file_get_contents("php://input"), true);
        } elseif ($this->contentType == self::CONTENT_TYPE_X_WWW_FORM_URLENCODED) {
            parse_str(file_get_contents("php://input"), $this->inputParamsArr);
        }

        $this->body = $this->inputParamsArr;
    }

    private function initInputFromGlobal()
    {
        switch ($this->method) {
            case self::METHOD_GET:
                $this->inputParamsArr = $_GET;
                break;
            default:
                $this->parseContentFromBody();
                break;
        }
    }

    private function generateUrl()
    {
        $port = $this->port == 80 || $this->port == 443 ? '' : $this->port;

        return sprintf('%s://%s%s%s', $this->scheme, $this->host, $port, $_SERVER['REQUEST_URI']);
    }

    public function initFromGlobal()
    {
        $this->method      = $_SERVER['REQUEST_METHOD'];
        $this->userAgent   = $_SERVER['HTTP_USER_AGENT'];
        $this->contentType = $_SERVER['CONTENT_TYPE'];
        $this->remoteIp    = $_SERVER['REMOTE_ADDR'];
        $this->remotePort  = $_SERVER['REMOTE_PORT'];
        $this->isKeepAlive = isset($_SERVER['HTTP_CONNECTION']) && $_SERVER['HTTP_CONNECTION'] == 'keep-alive' ? true : false;

        $this->scheme   = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $this->user     = !empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $this->password = !empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        $this->host     = $_SERVER['HTTP_HOST'];
        $this->port     = $_SERVER['REMOTE_PORT'];

        $reqUrlExplode = explode('?', $_SERVER['REQUEST_URL']);
        $this->path    = $reqUrlExplode[0];
        $this->query   = !empty($reqUrlExplode[1]) ? $reqUrlExplode[1] : '';
        $this->url     = $this->generateUrl();

        $this->initInputFromGlobal();
    }

    public function path()
    {
        return $this->path;
    }

    public function url()
    {
        return $this->url;
    }

    public function input($name, $default = '')
    {
        if (empty($name)) {
            return $default;
        }

        $keyArr = explode('.', $name);
        $result = $this->inputParamsArr;

        foreach ($keyArr as $key) {
            if (!isset($result[$key])) {
                return $default;
            }
            $result = $result[$key];
        }

        return $result;
    }

    public function method()
    {
        return $this->method;
    }

    public function isMethod($method)
    {
        return $this->method == $method;
    }

    public function has($name)
    {
        if (empty($name)) {
            return false;
        }
        $keyArr = explode('.', $name);
        $result = $this->inputParamsArr;

        foreach ($keyArr as $key) {
            if (!isset($result[$key])) {
                return false;
            }
            $result = $result[$key];
        }

        return true;
    }

    public function all()
    {
        return $this->inputParamsArr;
    }

    public function body()
    {
        return $this->body;
    }

    public function session()
    {
        return $this->session;
    }

    public function cookieHandle()
    {
        return $this->cookieHandler;
    }

}

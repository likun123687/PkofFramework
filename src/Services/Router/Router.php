<?php

namespace Pkof\Services\Router;

use Pkof\Services\Request\Request;
use Pkof\Services\MiddlewareProcess\MiddlewareProcess;
use Pkof\Exceptions\Error\InvalidArgumentWithContextException;
use Pkof\Exceptions\Error\RuntimeWithContextException;

/**
 * Class Router
 * @package Pkof\Services\Router
 */
class Router
{
    const LETTER_AND_NUMBER_RE = '/\{[A-Za-z0-9]+\}/';

    private $commonRules   = [];
    private $rexRules      = [];
    private $groupArr      = [];
    private $rexPatternArr = [];

    private $allowMethod = ['get', 'post', 'put', 'delete', 'any'];

    private $requestMethod = '';
    private $requestUrl    = '';

    private $flag = '';

    protected $request;
    protected $middleProcess;

    public function __construct(Request $request, MiddlewareProcess $middleProcess)
    {
        $this->request       = $request;
        $this->middleProcess = $middleProcess;
    }

    /**
     * @param $method
     * @param $params
     *
     * @return $this
     */
    public function __call($method, $params)
    {
        if (!in_array($method, $this->allowMethod)) {
            throw new InvalidArgumentWithContextException('Not support method', $method);
        }
        if (count($params) !== 2) {
            throw new InvalidArgumentWithContextException('Wrong router params register, url or callback can not be empty string.');
        }

        $url      = $params[0];
        $callback = $params[1];

        if ($url == "" || $callback == "") {
            throw new RuntimeWithContextException('Wrong router params register, url or callback can not be empty string.');
        }

        if (is_string($callback) && strpos(trim($callback, '@'), '@') == false) {
            throw new InvalidArgumentWithContextException('Controller and method router error', $callback);
        }

        if ($method == 'any') {
            $methodArr = array_slice($this->allowMethod, 0, count($this->allowMethod) - 1);
        } else {
            $methodArr = [$method];
        }

        $routeArr = [];
        foreach ($methodArr as $methodItem) {
            $routeArr[$methodItem] = ['callback' => $callback];
        }

        if (strpos($url, '{') === false) {
            $this->commonRules[$url] = $routeArr;
        } else {
            $this->rexRules[$url] = $routeArr;
        }

        $this->flag = $url;

        return $this;
    }

    /**
     * @param $patternArr
     *
     * @return $this
     */
    public function where($patternArr)
    {
        if (!is_array($patternArr)) {
            throw new InvalidArgumentWithContextException('Where params error.', $patternArr);
        }

        if (empty($this->flag)) {
            return $this;
        }

        $this->rexPatternArr[$this->flag] = $patternArr;

        return $this;
    }

    /**
     * @param $groupName
     *
     * @return $this
     */
    public function group($groupName)
    {
        if (empty($groupName)) {
            throw new InvalidArgumentWithContextException('Group name can not empty.', $groupName);
        }

        if (empty($this->flag)) {
            return $this;
        }

        if (empty($this->groupArr[$groupName])) {
            $this->groupArr[$groupName] = [];
        }
        $this->groupArr[$groupName][] = $this->flag;

        return $this;
    }

    /**
     * @param       $url
     * @param       $callback
     * @param array $matchParams
     */
    private function callbackHandle($url, $callback, $matchParams = [])
    {
        $callFunc   = NULL;
        $callParams = $matchParams;

        if (is_object($callback) && ($callback instanceof \Closure)) {
            $reflect  = new \ReflectionFunction($callback);
            $params   = $reflect->getParameters();
            $callFunc = $reflect;

        } elseif (is_string($callback)) {
            $ctrl = explode('@', $callback);
            if (count($ctrl) !== 2) {
                throw new RuntimeWithContextException('Error callback register.' . $callback);
            }
            $controller_name = $ctrl[0];
            $method_name     = $ctrl[1];

            $reflect  = new \ReflectionClass($controller_name);
            $callFunc = $reflect->getMethod($method_name);
            $params   = $callFunc->getParameters();

        } else {
            throw new InvalidArgumentWithContextException('Params error', $callback);
        }

        if (count($params) > 0 && $params[0] instanceof Request) {
            array_unshift($callParams, $this->request);
        }

        //before middle handle
        $this->middleProcess->before($url, $matchParams);
        call_user_func_array($callFunc, $callParams);
        //after middle handle
        $this->middleProcess->after($url, $matchParams);
    }


    /**
     * dispatch the router
     */
    public function dispatch()
    {
        //common rules
        if (isset($this->commonRules[$this->requestUrl])) {
            if (isset($this->commonRules[$this->requestUrl][$this->requestMethod])) {
                $callback = $this->commonRules[$this->requestUrl][$this->requestMethod]['callback'];
                $this->callbackHandle($this->requestUrl, $callback);
            }
        } else {
            foreach ($this->rexRules as $url => $rule) {
                $pattern = isset($this->rexPatternArr[$url]) ? $this->rexPatternArr[$url] : [];
                $tokens  = [];
                $result  = preg_match_all(self::LETTER_AND_NUMBER_RE, $url, $tokens);

                if ($result === false) {
                    throw new RuntimeWithContextException('Rex Error happen.', preg_last_error());
                } elseif ($result == 0) {
                    throw new InvalidArgumentWithContextException('Error router url register', [$url, $rule]);
                }

                $params = $tokens[0];
                $rexArr = [];
                foreach ($params as $token) {
                    if (isset($pattern[trim($token, '{}')])) {
                        $rexArr[] = '(' . $pattern[trim($token, '{}')] . ')';
                    } else {
                        $rexArr[] = '([A-Za-z0-9]+)';
                    }
                }

                $rexUrl      = str_replace($params, $rexArr, $url);
                $matchResult = [];

                if (preg_match('/' . str_replace('/', '\/', $rexUrl) . '/', $this->requestUrl, $matchResult) && $matchResult[0] == $this->requestUrl) {
                    if (isset($rule[$this->requestMethod]['callback'])) {
                        $callback = $rule[$this->requestMethod]['callback'];
                        $this->callbackHandle($url, $callback, array_slice($matchResult, 1));
                        break;
                    }
                }
            }
        }
    }
}

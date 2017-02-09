<?php
/**
 * @filename php/pkof/app/Services/Router/Router.php
 * @touch    15/01/2017 13:37
 * @author   likun <378186878@qq.com>
 * @version  1.0.0
 */
namespace Pkof\Services\Router;

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
     * @throws NotSupportMethodException
     */
    public function __call($method, $params)
    {
        if (!in_array($method, $this->allowMethod)) {
            throw new NotSupportMethodException('not support method');
        }
        if (count($params) !== 2) {
            throw new \Exception('wrong router params register, url or callback can not be empty string.');
        }

        $url      = $params[0];
        $callback = $params[1];

        if ($url == "" || $callback == "") {
            throw new \Exception('wrong router params register, url or callback can not be empty string.');
        }

        if (is_string($callback) && strpos(trim($callback, '@'), '@') == false) {
            throw new \Exception('controller and method router error');
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
     * @throws \Exception
     */
    public function where($patternArr)
    {
        if (!is_array($patternArr)) {
            throw new \Exception('params error');
        }

        if (empty($this->flag)) {
            return $this;
        }

        $this->rexPatternArr[$this->flag] = $patternArr;

        return $this;
    }

    /**
     * Add router to group
     *
     * @param $groupName
     *
     */
    public function group($groupName)
    {
        if (empty($groupName)) {
            throw new \Exception('group name can not empty');
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

    private function callbackHandle($url, $callback, $matchParams = [])
    {
        $call_func   = NULL;
        $call_params = $matchParams;
        $params      = [];

        if (is_object($callback) && ($callback instanceof \Closure)) {
            $reflect   = new \ReflectionFunction($callback);
            $params    = $reflect->getParameters();
            $call_func = $reflect;

        } elseif (is_string($callback)) {
            $ctrl = explode('@', $callback);
            if (count($ctrl) !== 2) {
                throw new \Exception('error callback register' . $callback);
            }
            $controller_name = $ctrl[0];
            $method_name     = $ctrl[1];

            $reflect   = new ReflectionClass($controller_name);
            $call_func = $reflect->getMethod($method_name);
            $params    = $reflect->getParameters();

        } else {
            throw new Exception('params error');
        }

        if (count($params) > 0 && $params[0] instanceof Request) {
            array_unshift($call_params, $this->request);
        }

        //before middle handle
        $this->middleProcess->beforeHandle($url, $matchParams);

        call_user_func_array($call_func, $call_params);

        //after middle handle
        $this->middleProcess->afterHandle($url, $matchParams);
    }

    public function before($middleware)
    {
        $this->middleProcess->before($this->flag, $middleware);
    }

    public function after($middleware)
    {
        $this->middleProcess->after($this->flag, $middleware);
    }

    public function dispatch()
    {
        //common rules
        if (isset($this->commonRules[$this->requestUrl])) {
            if (isset($this->commonRules[$this->requestUrl][$this->requestMethod])) {
                $callback = $this->commonRules[$this->requestUrl][$this->requestMethod]['callback'];
                $this->callbackHandle($url, $callback);
            }
        } else {
            foreach ($this->rexRules as $url => $rule) {
                $pattern = isset($this->patternArr[$url]) ? $this->patternArr[$url] : [];
                $tokens  = [];
                $result  = preg_match_all(self::LETTER_AND_NUMBER_RE, $url, $tokens);

                //check error
                if ($result === false) {
                    throw new \Exception('error happen');
                } elseif ($result == 0) {
                    throw new \Exception('Error router url register');
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

                //match the request url
                if (preg_match('/' . str_replace('/', '\/', $rexUrl) . '/', $this->requestUrl, $matchResult) && $matchResult[0] == $this->requestUrl) {
                    if (isset($rule[$this->requestMethod]['callback'])) {
                        $callback = $rule[$this->requestMethod]['callback'];
                        $this->callbackHandle($url, $callback, array_slice(1, $matchResult));
                        break;
                    }
                }
            }
        }
    }
}

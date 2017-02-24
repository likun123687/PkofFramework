<?php
namespace Pkof\Services\Validator;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/13
 * Time: PM9:11
 */
abstract class Validator
{
    private $customErrorsWithInputName;
    private $customErrors;

    protected $errors;
    protected $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function getError($attr)
    {
        return $this->errors[$attr];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    abstract public function rules();

    abstract public function names();

    public function customerError()
    {
        return [];
    }

    public function defaultErrorText()
    {
        return [
            "required" => "this field is required",
        ];
    }

    /**
     * @param $rule
     *
     * @return array
     */
    private function getParams($rule)
    {
        if (preg_match("#^([a-zA-Z0-9_]+)\((.+?)\)$#", $rule, $matches)) {
            return array(
                'rule'   => $matches[1],
                'params' => explode(",", $matches[2])
            );
        }

        return array(
            'rule'   => $rule,
            'params' => array()
        );
    }

    /**
     * @param $params
     * @param $inputs
     *
     * @return mixed
     */
    private function getParamValues($params)
    {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                $params[$key] = @$this->input[(string)$param_type[1]];
            }
        }

        return $params;
    }

    /**
     * @param $attr
     *
     * @return mixed
     */
    private function handleName($attr)
    {
        $names = $this->names();
        if (isset($names[(string)$attr])) {
            $name = $names[(string)$attr];
        } else {
            $name = $attr;
        }

        return $name;
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    protected function handleParameterNaming($params)
    {
        $names = $this->names();

        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                if (isset($names[(string)$param_type[1]]))
                    $params[$key] = $names[(string)$param_type[1]];
                else
                    $params[$key] = $param_type[1];
            }
        }

        return $params;
    }

    /**
     * parse Customer Error
     */
    protected function parseCustomerError()
    {
        $customerErrors = $this->customerError();
        foreach ($customerErrors as $key => $value) {
            // handle input.rule eg (name.required)
            if (preg_match("#^(.+?)\.(.+?)$#", $key, $matches)) {
                // $this->customErrorsWithInputName[name][required] = error message
                $this->customErrorsWithInputName[(string)$matches[1]][(string)$matches[2]] = $value;
            } else {
                $this->customErrors[(string)$key] = $value;
            }
        }
    }

    /**
     * parse error
     */
    private function parseError()
    {
        $defaultErrorTexts = $this->defaultErrorText();
        $this->parseCustomerError();

        foreach ($this->errors as $attr => $results) {
            foreach ($results as $rule => $result) {
                $attrName         = $this->handleName($attr);
                $result['params'] = $this->handleParameterNaming($result['params']);

                if (isset($this->customErrorsWithInputName[(string)$attrName][(string)$rule])) {
                    $errorMessage = $this->customErrorsWithInputName[(string)$attrName][(string)$rule];
                } // if there is a custom message for the rule, apply it
                else if (isset($this->customErrors[(string)$rule])) {
                    $errorMessage = $this->customErrors[(string)$rule];
                } else if (isset($defaultErrorTexts[(string)$rule])) {
                    $errorMessage = $defaultErrorTexts[(string)$rule];
                } else {
                    throw new \RuntimeException("not foud customers message");
                }

                if (preg_match_all("#:params\((.+?)\)#", $errorMessage, $paramIndexes))
                    foreach ($paramIndexes[1] as $paramIndex) {
                        $errorMessage = str_replace(":params(" . $paramIndex . ")", $result['params'][$paramIndex], $errorMessage);
                    }
                $this->errors[$attr][] = str_replace(":attribute", $attrName, $errorMessage);
            }
        }
    }

    /**
     * validate
     */
    public function validate()
    {
        $rules = $this->rules();

        foreach ($rules as $attr => $ruleArr) {
            if (is_array($ruleArr)) {
                foreach ($ruleArr as $rule => $closure) {
                    if (!isset($this->input[(string)$attr]))
                        $inputValue = NULL;
                    else
                        $inputValue = $this->input[(string)$attr];

                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }

                    $ruleAndParams = $this->getParams($rule);
                    $params        = $realParams = $ruleAndParams['params'];
                    $rule          = $ruleAndParams['rule'];

                    $params = $this->getParamValues($params);
                    array_unshift($params, $inputValue);

                    if (is_object($closure) && get_class($closure) == 'Closure') {
                        $reflFunc   = new \ReflectionFunction($closure);
                        $validation = $reflFunc->invokeArgs($params);

                    } else if (@method_exists(get_called_class(), $rule)) {
                        $refl = new \ReflectionMethod(get_called_class(), $rule);
                        if (!$refl->isStatic()) {
                            $refl->setAccessible(true);
                            $validation = $refl->invokeArgs(NULL, $params);
                        } else {
                            throw new \RuntimeException("Validator method must not be static :");
                        }
                    } else {
                        throw new \RuntimeException("Validator method must not be static");
                    }
                    if ($validation == false) {
                        $this->errors[(string)$attr][(string)$rule]['result'] = false;
                        $this->errors[(string)$attr][(string)$rule]['params'] = $realParams;
                    }
                }
            } else {
                throw new \RuntimeException("Unkown rules");
            }
        }

        $this->parseError();
    }
}
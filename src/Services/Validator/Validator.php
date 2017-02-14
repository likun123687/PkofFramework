<?php
namespace Pkof\Services\Validator;
/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/13
 * Time: PM9:11
 */
class Validator
{
    protected $rules;
    protected $errors;
    protected $data;

    public function __construct($data = [], $rules = [])
    {
        $this->data  = $data;
        $this->rules = $rules;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getError($attr)
    {

    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function validate()
    {
        foreach ($this->rules as $input => $input_rules) {
            if (is_array($input_rules)) {
                foreach ($input_rules as $rule => $closure) {
                    if (!isset($inputs[(string)$input]))
                        $input_value = NULL;
                    else
                        $input_value = $inputs[(string)$input];
                    /**
                     * if the key of the $input_rules is numeric that means
                     * it's neither an anonymous nor an user function.
                     */
                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }
                    $rule_and_params = static::getParams($rule);
                    $params          = $real_params = $rule_and_params['params'];
                    $rule            = $rule_and_params['rule'];
                    $params          = static::getParamValues($params, $inputs);
                    array_unshift($params, $input_value);
                    /**
                     * Handle anonymous functions
                     */
                    if (is_object($closure) && get_class($closure) == 'Closure') {
                        $refl_func  = new \ReflectionFunction($closure);
                        $validation = $refl_func->invokeArgs($params);
                    } /**
                     * handle class methods
                     */ else if (@method_exists(get_called_class(), $rule)) {
                        $refl = new \ReflectionMethod(get_called_class(), $rule);
                        if ($refl->isStatic()) {
                            $refl->setAccessible(true);
                            $validation = $refl->invokeArgs(NULL, $params);
                        } else {
                            throw new SimpleValidatorException(SimpleValidatorException::STATIC_METHOD, $rule);
                        }
                    } else {
                        throw new SimpleValidatorException(SimpleValidatorException::UNKNOWN_RULE, $rule);
                    }
                    if ($validation == false) {
                        $errors[(string)$input][(string)$rule]['result'] = false;
                        $errors[(string)$input][(string)$rule]['params'] = $real_params;
                    }
                }
            } else {
                throw new SimpleValidatorException(SimpleValidatorException::ARRAY_EXPECTED, $input);
            }
        }
    }

    public function customErrors($errorArray)
    {

    }

    public function customName($nameArray)
    {

    }
}
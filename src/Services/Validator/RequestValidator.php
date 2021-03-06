<?php
namespace Pkof\Services\Validator;

use Pkof\Services\Request\Request;
use Pkof\Services\Validator\ValidatorException\ValidatorException;

/**
 * Class RequestValidator
 * @package Pkof\Services\Validator
 */
class RequestValidator extends Validator
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request->all());
    }

    public function rules()
    {
        return [
            "id"   => ['required', 'unique'],
            "name" => ['required']
        ];
    }

    public function names()
    {
        return [
            "id"   => 'UserId',
            "name" => "Name",
        ];
    }

    public function customerError()
    {
        return [
            "customer.required" => 'I am a customer error',
        ];
    }

    public function validate()
    {
        parent::validate();
        $err = $this->errors;
        if (!empty($err)) {
            throw  new ValidatorException($err);
        }
    }
}
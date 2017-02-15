<?php
namespace Pkof\Services\Validator;

use Pkof\Services\Request\Request;
use Pkof\Services\Response\Response;

/**
 * Created by PhpStorm.
 * User: likun
 * Date: 17/2/13
 * Time: PM9:24
 */
class RequestValidator extends Validator
{
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
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
            "customer.sdff" => 'sdff',
        ];
    }

    public function validate()
    {
        parent::validate();
        if (!empty($this->errors)) {
            throw 
        }
    }
}
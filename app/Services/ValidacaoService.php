<?php

namespace App\Services;

use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;

class ValidacaoService
{
    private $rules;
    private $data;

    public function __construct(array $rules, array $data)
    {
        $this->rules = $rules;
        $this->data = $data;
    }
    
    
    public function make()
    {
        $validator = Validator::make($this->data, $this->rules);
        
        if ($validator->fails()) {
            Helper::setNotify($validator->messages(), 'danger|close-circle');
            return $validator;
        }
        return false;
    }
}

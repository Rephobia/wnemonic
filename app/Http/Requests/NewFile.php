<?php

namespace App\Http\Requests;


use App\Literal;
use App\Rules\FileRule;
use App\Http\Requests\BasicRequest;

class NewFile extends BasicRequest
{    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return array(Literal::nameField() => (new FileRule($this))->required()->isFile()->unique());
    }
  
}

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
        $nameRules = (new FileRule($this))->required()->isFile()->unique();
        $tagRules = (new FileRule($this))->required();

        return array(Literal::nameField() => $nameRules,
                     Literal::tagField() => $tagRules);
    }
  
}

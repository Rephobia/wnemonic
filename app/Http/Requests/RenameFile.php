<?php

namespace App\Http\Requests;

use App\Literal;
use App\Rules\FileRule;
use App\Http\Requests\BasicRequest;


class RenameFile extends BasicRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {        
        $rules = array(Literal::nameField() => (new FileRule($this))->exists(),
                       Literal::newnameField() => (new FileRule($this))->unique());

        return $rules;
    }
    
    public function attributes() : array
    {
        return [
            Literal::newnameField() => "new name"
        ];
    }    
}

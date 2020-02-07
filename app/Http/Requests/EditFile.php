<?php

namespace App\Http\Requests;

use App\Literal;
use App\Rules\FileRule;
use App\Http\Requests\BasicRequest;

class EditFile extends BasicRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        $nameRules = (new FileRule($this))->required()->exists();
        
        $newnameRules = (new FileRule($this))->required()->unique(Literal::nameField());
        
        $tagRules = (new FileRule($this))->required();
        
        $rules = array(Literal::nameField() => $nameRules,
                       Literal::newnameField() => $newnameRules,
                       Literal::tagField() => $tagRules);
        
        return $rules;
    }
    
    public function attributes() : array
    {
        return [
            Literal::newnameField() => "new name"
        ];
    }    
}

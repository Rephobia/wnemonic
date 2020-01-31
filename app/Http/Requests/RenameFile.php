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
        $nameRules = (new FileRule($this))->required()->exists();
        
        $newnameRules = (new FileRule($this))
                      ->required()
                      ->equalField(Literal::nameField())
                      ->unique();
        
        $rules = array(Literal::nameField() => $nameRules,
                       Literal::newnameField() => $newnameRules);
        
        return $rules;
    }
    
    public function attributes() : array
    {
        return [
            Literal::newnameField() => "new name"
        ];
    }    
}

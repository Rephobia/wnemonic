<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Literal;
use App\Rules\FileRule;


class RenameFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {        
        $rules = array(Literal::nameField() => (new FileRule)->exists(),
                       Literal::newnameField() => (new FileRule)->unique($this));

        return $rules;
    }
    
    public function attributes() : array
    {
        return [
            Literal::newnameField() => "new name"
        ];
    }
    
    protected $redirect = "/";
}

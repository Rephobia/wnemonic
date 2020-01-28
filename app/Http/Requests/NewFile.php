<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Literal;
use App\Rules\IsFile;
use App\Rules\UniqueFile;

class NewFile extends FormRequest
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
        $extendRules = Literal::nameRules(new IsFile($this), new UniqueFile);
        $rules = array(Literal::nameField() => $extendRules);
        return $rules;
    }
}

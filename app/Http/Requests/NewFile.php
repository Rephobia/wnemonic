<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\IsFile;
use App\Rules\UniqueFile;
use App\Utils\Constants\FileName;

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
        $extendRules = FileName::rules(new IsFile($this), new UniqueFile);
        $rules = array(FileName::nameField() => $extendRules);
        return $rules;
    }
}

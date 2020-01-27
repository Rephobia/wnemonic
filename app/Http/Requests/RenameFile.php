<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Utils\Constants\FileName;

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
        $basicRules = FileName::rules();
        
        $rules = array(FileName::nameField() => $basicRules,
                       FileName::newnameField() => $basicRules);

        return $rules;
    }
    
    public function attributes() : array
    {
        return [
            FileName::newnameField() => "new name"
        ];
    }
}

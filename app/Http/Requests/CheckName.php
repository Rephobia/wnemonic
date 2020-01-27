<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Utils\Constants\FileName;

class CheckName extends FormRequest
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
        return array(FileName::nameField() => FileName::rules());
    }        
}

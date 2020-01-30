<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Literal;
use App\Rules\FileRule;

class CheckFile extends FormRequest
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
        return array(Literal::nameField() => (new FileRule)->exists());
    }
    
    protected $redirect = "/";
}

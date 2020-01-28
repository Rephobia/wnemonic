<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\FileDetail;
use App\Literal;

class UniqueFile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    { ;}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->filename = $value->getClientOriginalName();
        return !FileDetail::where(Literal::nameField(), "=", $this->filename)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "File '".$this->filename."' already exists.";
    }
    private $filename;
}

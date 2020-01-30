<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Repository;

class FileExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->filename = $value;
        return Repository::get($value) !== NULL;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "File '{$this->filename}' isn't exist.";
    }
    
    private $filename;
}

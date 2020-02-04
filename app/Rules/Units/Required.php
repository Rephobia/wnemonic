<?php

namespace App\Rules\Units;

class Required extends BasicRule
{
    public function fails($attribute, $value) : bool
    {
        return $value === NULL;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return ":attribute is required";
    }
    
    private $value;
}

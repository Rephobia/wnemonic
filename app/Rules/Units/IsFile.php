<?php

namespace App\Rules\Units;


class IsFile extends BasicRule
{
    public function fails($attribute, $value, $request) : bool
    {
        return !$request->hasFile($attribute);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return "The passed value isn't a file";
    }    
}

<?php

namespace App\Rules\Units;

use App\Repository;

class Unique extends BasicRule
{
    public function fails($attribute, $value, $request)
    {
        $isFile = $request->hasFile($attribute);
        $this->value = $isFile ? $value->getClientOriginalName() : $value;
        
        return Repository::get($this->value) !== NULL;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    
    public function message() : string
    {
        return "File '{$this->value}' already exists.";
    }
    
    private $value;
}

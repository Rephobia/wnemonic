<?php

namespace App\Rules\Units;

use App\Repository;

class Exists extends BasicRule
{

    public function __construct()
    {
        BasicRule::setRedirect("/");
    }

    public function fails($attribute, $value)
    {
        $this->value = $value;
        return Repository::get($value) === NULL;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "File '{$this->value}' isn't exist.";
    }
    
    private $value;
}

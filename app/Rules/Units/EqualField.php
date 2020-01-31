<?php

namespace App\Rules\Units;

use App\Repository;

class EqualField extends BasicRule
{

    public function __construct(string $field)
    {
        $this->equalField = $field;
    }

    public function fails($attribute, $value, $request)
    {
        return $value === $request->input($this->equalField);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute is equal old {$this->equalField}";
    }
    
    private $equalField;

}

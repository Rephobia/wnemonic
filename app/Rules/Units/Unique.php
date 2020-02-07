<?php

namespace App\Rules\Units;

use App\Repository;

class Unique extends BasicRule
{
    public function __construct(string $ignoreField)
    {
        $this->ignoreField = $ignoreField;
    }
    
    public function fails($attribute, $value, $request)
    {
        $isFile = $request->hasFile($attribute);
        $this->value = $isFile ? $value->getClientOriginalName() : $value;

        $notEqualIgnore = $this->value !== $request->input($this->ignoreField);
        $inRepository = \App::make(Repository::class)->get($this->value) !== NULL;
        
        return $notEqualIgnore && $inRepository;
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
    private $ignoreField;
}

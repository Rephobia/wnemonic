<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

use App\Rules\IsFile;
use App\Rules\UniqueFile;
use App\Rules\FileExists;


class FileRule implements ImplicitRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->rules as $rule) {
            
            if (!$rule->passes($attribute, $value)) {
                $this->errorMessage = $rule->message();
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }

    public function exists()
    {
        array_push($this->rules, new FileExists);
        
        return $this;
    }
    public function unique($request)
    {
        array_push($this->rules, new UniqueFile ($request));
        
        return $this;
    }
    
    public function isFile($request)
    {
        array_push($this->rules, new IsFile ($request));
        
        return $this;
    }

    protected $rules = array();
    protected $errorMessage = "";
    
}

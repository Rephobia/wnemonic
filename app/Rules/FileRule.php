<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

use App\Rules\Units\BasicRule;
use App\Rules\Units\Required;
use App\Rules\Units\IsFile;
use App\Rules\Units\Unique;
use App\Rules\Units\Exists;


class FileRule implements ImplicitRule
{
    /**
     * Create a new rule instance.
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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
        foreach ($this->rules as $rule) {
            
            if ($rule->fails($attribute, $value, $this->request)) {
                
                $this->errorMessage = $rule->message();
                $this->request->setRedirect($rule->getRedirect());
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
    
    public function required()
    {
        return $this->appendRule(new Required);
    }

    public function exists()
    {        
        return $this->appendRule(new Exists);
    }
    
    public function unique()
    {        
        return $this->appendRule(new Unique);
    }
    
    public function isFile()
    {
        return $this->appendRule(new IsFile);
    }
    
    protected function appendRule(BasicRule $rule)
    {
        array_push($this->rules, $rule);
        return $this;
    }

    protected $rules = array();
    protected $errorMessage = "";
    protected $request;    
}

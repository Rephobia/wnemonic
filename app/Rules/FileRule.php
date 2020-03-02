<?php

/*
 * SPDX-License-Identifier: AGPL-3.0-or-later

 * Copyright (C) 2020 Roman Erdyakov

 * This file is part of Wnemonic. It is a tag based file manager.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/


namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

use App\Rules\Units\BasicRule;
use App\Rules\Units\Required;
use App\Rules\Units\EqualField;
use App\Rules\Units\EqualFileField;
use App\Rules\Units\IsFile;
use App\Rules\Units\Unique;
use App\Rules\Units\Exists;
use App\Rules\Units\CheckPass;



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

    public function equalField(string $field)
    {
        return $this->appendRule(new EqualField($field));
    }
    
    public function equalFileField(string $field)
    {
        return $this->appendRule(new EqualFileField($field));
    }

    public function exists()
    {        
        return $this->appendRule(new Exists);
    }
    
    public function unique(string $ignoreField = "")
    {        
        return $this->appendRule(new Unique ($ignoreField));
    }
    
    public function isFile()
    {
        return $this->appendRule(new IsFile);
    }
    
    public function checkPass()
    {
        return $this->appendRule(new CheckPass);
    }
    
    protected function appendRule(BasicRule $rule)
    {
        array_push($this->rules, $rule);
        return $this;
    }

    protected $rules = array();
    protected $errorMessage = "";
}

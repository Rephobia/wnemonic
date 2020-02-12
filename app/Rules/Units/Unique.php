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

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


namespace App;

use Illuminate\Support\Facades\Storage;

use App\Utils\FileInfo;


class FileView
{
    public function __construct($file, $filesystem)
    {
        $this->file = $file;
        $this->filesystem = $filesystem;
    }
    
    public function name() : string
    {
        return $this->file->name;
    }
    public function updated()
    {
        return $this->file->updated_at;
    }


    public function path() : string
    {
        return FileInfo::hashPath($this->file->name);
    }
    
    public function link() : string
    {
        return $this->filesystem->url($this->path());
    }
    
    public function content() : string
    {
        return $this->filesystem->get($this->path());
    }
    
    public function type() : string
    {
        return $this->filesystem->mimeType($this->path());
    }
    
    public function tags() : array
    {
        if (empty($this->tags)) {
            
            foreach ($this->file->tags as $tag) {

                $tagName = $tag[\App\Literal::tagsField()];
                
                array_push($this->tags, $tagName);
                    
            }
        }
        
        return $this->tags;
    }
    
    public function tagsString() : string
    {
        return implode(",", $this->tags());
    }
    
    public function __get($field)
    {
        $trace = debug_backtrace();
        $incomer = $trace[1]['class'];
        
        if (isset($incomer) && in_array($incomer, $this->friends)) {
            return $this->file;
        }

        trigger_error("{$incomer} cannot access to private field ".__CLASS__."::".$field,
                      E_USER_ERROR);
    }
    
    private $file;
    private $tags = array();
    private $filesystem;
    
    
    private $friends = array("App\Repository");
}

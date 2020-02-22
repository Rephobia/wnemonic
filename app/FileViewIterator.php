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

class FileViewIterator implements \Iterator
{
    public function __construct($paginator, $filesystem)
    {
        $this->paginator = $paginator;
        $this->position = 0;
        $this->pages = $this->createLinks();
        
        $this->filesystem = $filesystem;
    }
    public function pages() : string
    {
        return $this->pages;
    }
    
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return new FileView ($this->paginator[$this->position], $this->filesystem);
    }
    
    public function key()
    {
        return $this->position;
    }
    public function next()
    {
        ++$this->position;
    }
    
    public function valid()
    {
        return isset($this->paginator[$this->position]);
    }

    private function createLinks() : string
    {
        $links = $this->paginator->links();
        
        $paramPattern = "#\?page=#";
        $paramReplace = "/page/";
        $transition = preg_replace($paramPattern, $paramReplace, $links);

        $argumPattern = "#page/([1-9]+[0-9]*)/page/([1-9]+[0-9]*)#";
        $argumReplace = "page/$2";
        
        return preg_replace($argumPattern, $argumReplace, $transition);
                
    }
    
    private $paginator;
    private int $position = 0;
    private string $pages;

    private $filesystem;
}

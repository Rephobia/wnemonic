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


namespace Tests\Feature;

use Illuminate\Http\UploadedFile;

class FakeFile
{
    public function __construct($name = "fileName", $tags = "tag1, tag2", $kilobytes = 1024)
    {
        $this->name = $name;
        $this->tags = $tags;
        $this->kilobytes = $kilobytes;
        
        $this->file = UploadedFile::fake()->create($this->name, $this->kilobytes);
    }
    public function file() : UploadedFile
    {
        return $this->file;
    }
    public function name() : string
    {
        return $this->name;
    }
    public function tags() : string
    {
        return $this->tags;
    }
    public function size() : int
    {
        return $this->size;
    }
    
    private string $name;
    private string $tags;
    private int $kilobytes;
    private UploadedFile $file;
}

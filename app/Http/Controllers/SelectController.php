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


<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Literal;
use App\FileView;
use App\Repository;


class SelectController extends Controller
{
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function file(string $filename)
    {
        $file = $this->repository->get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function files(int $page = 1)
    {
        $files = $this->repository->files($page);
        
        return view("main")->with("files", $files);
    }

    public function filesByTags(string $tags, int $page = 1)
    {
        $files = $this->repository->filesByTags($tags, $page);
        
        return view("main")->with("files", $files);
    }
    

    private $repository;
}

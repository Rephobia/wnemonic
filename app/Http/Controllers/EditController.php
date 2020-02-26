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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repository;
use App\Literal;
use App\Http\Requests\DeleteFile;
use App\Http\Requests\EditFile;
use App\Http\Requests\NewFile;


class EditController extends Controller
{    
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function getEditForm(string $fileName)
    {
        $file = $this->repository->get($fileName);
        
        if ($file === NULL) {
            abort(404);
        }
        session(["cancel_link" => "/".$fileName]);
        
        return view("editor/edit")->with("file", $file);
    }

    public function getAddForm()
    {
        session(["cancel_link" => url()->previous()]);
        
        return view("editor/add");
    }
    
    public function edit(EditFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        $newName = $request->input(Literal::newnameField());
        $newTags = $request->input(Literal::tagsField());
        
        $file = $this->repository->get($fileName);
        $this->repository->update($file, $newName, $newTags);
        
        return redirect("/".$newName);
    }
    
    public function add(NewFile $request)
    {
        $file = $request->file("file");
        $tags = $request->input(Literal::tagsField());

        $fileView = $this->repository->save($file, $tags);
        
        return redirect("/".$fileView->name());
    }
    
    public function delete(DeleteFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        $this->repository->delete($fileName);
        
        return redirect("/");
    }
    
    public function cancel()
    {
        return redirect(session("cancel_link"));
    }

    private $repository;
}

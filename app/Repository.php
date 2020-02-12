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
use Illuminate\Database\Eloquent\Model;
use App\Literal;
use App\Utils\FileInfo;
use App\Utils\TagMaker;



class File extends Model
{
    public function tags()
    {
        return $this->belongsToMany("\App\Tags")->withTimestamps();
    }
    
}

class Tags extends Model
{
    public function files()
    {
        return $this->belongsToMany("\App\File");
    }
    protected $fillable = array("tag");
}



class Repository
{
    public function get(string $fileName) : ?FileView
    {
        $data = self::getData($fileName);
                
        return empty($data) ? NULL : new FileView ($data);
    }

    public function files($page) : FileViewIterator
    {
        $paginator = File::paginate(self::pageCap, array("*"), "page", $page);
        return new FileViewIterator ($paginator);                       
    }

    public function filesByTags(string $tagsString, $page) : FileViewIterator
    {
        $tags = TagMaker::toArray($tagsString);

        $paginator = File::whereHas("tags", function($query) use ($tags) {
            $query->whereIn("tag", $tags);
        })->paginate(self::pageCap, array("*"), "page", $page);
                    
        return new FileViewIterator ($paginator);
    }


    public function save($file, string $tagsString) : FileView
    {
        $fileName = $file->getClientOriginalName();
        
        $data = new File;
        $data->name = $fileName;
        $data->save();
        
        $path = FileInfo::hashPath($fileName);
        Storage::putFileAs(".", $file, $path);
        
        $tags = TagMaker::toArray($tagsString);

        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagField() => $rawTag]);
            $data->tags()->attach($tag->id);
        }

        return new FileView ($data);
    }

    public function delete(string $fileName)
    {
        $data = self::getData($fileName);
        
        $path = FileInfo::hashPath($data->name);
        Storage::delete($path);
        $data->delete();
    }
    
    public function rename(FileView $fileView, string $newName) : void
    {
        $data = $fileView->data;
        
        $fileName = $data->name;
        
        if ($fileName === $newName) {
            return;
        }
        
        $data->name = $newName;
        $data->save();
        
        $oldpath = FileInfo::hashPath($fileName);
        $newpath = FileInfo::hashPath($newName);
        Storage::move($oldpath, $newpath);

    }

    public function updateTags(FileView $fileView, string $tagsString) : void
    {
        $data = $fileView->data;
        $tags = TagMaker::toArray($tagsString);
        
        $tagsId = array();
        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagField() => $rawTag]);
            array_push($tagsId, $tag->id);
        }
        
        $data->tags()->sync($tagsId);
    }

    
    
    private function getData(string $fileName) : ?File
    {
        $data = File::where(Literal::nameField(), "=", $fileName)->first();

        return $data;
    }

    const pageCap = 13;
}

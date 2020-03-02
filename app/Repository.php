<?php

/*
 * SPDX-License-Identifier: AGPL-3.0-or-later

 * Copyright (C) 2020 Roman Erdyakov

 * This file is part of Wnemonic. It is a tags based file manager.

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

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Filesystem\Filesystem;

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
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    public function get(string $fileName) : ?FileView
    {
        $file = $this->getFile($fileName);
                
        return empty($file) ? NULL : new FileView ($file, $this->filesystem);
    }

    public function files($page) : FileViewIterator
    {
        $paginator = File::paginate(self::pageCap, array("*"), "page", $page);
        return new FileViewIterator ($paginator, $this->filesystem);
    }

    public function search(string $tags, $page) : FileViewIterator
    {
        $tagsArr = TagMaker::toArray($tags);

        $query = File::query();

        $query->whereHas("tags", function($query) use ($tagsArr)
        {
            $query->whereIn(Literal::tagsField(), $tagsArr);
            
        }, "=", count($tagsArr));
        
        $paginator = $query->paginate(self::pageCap, array("*"), "page", $page);
        
        return new FileViewIterator ($paginator, $this->filesystem);
    }

    public function save($uploadedFile, string $newTags) : FileView
    {
        $fileName = $uploadedFile->getClientOriginalName();
        
        $file = new File;
        $file->name = $fileName;
        $file->save();
        
        $path = FileInfo::hashPath($fileName);
        $this->filesystem->putFileAs(".", $uploadedFile, $path);
        
        $tags = TagMaker::toArray($newTags, $fileName);
        
        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagsField() => $rawTag]);
            $file->tags()->attach($tag->id);
        }

        return new FileView ($file, $this->filesystem);
    }

    public function delete(string $fileName)
    {
        $file = $this->getFile($fileName);
        
        $path = FileInfo::hashPath($file->name);
        
        $this->filesystem->delete($path);
        $file->delete();
    }
    
    public function update(FileView $fileView, string $newName,
                           string $newTags) : void
    {
        $file = $fileView->file;
        
        $this->updateName($file, $newName);
        $this->updateTags($file, $newTags);
    }
    
    private function updateName(File $file, string $newName) : void
    {
        $fileName = $file->name;
        
        if ($fileName !== $newName) {
            
            $file->name = $newName;
            $file->save();
        
            $oldpath = FileInfo::hashPath($fileName);
            $newpath = FileInfo::hashPath($newName);
        
            $this->filesystem->move($oldpath, $newpath);
            
        }
    }

    private function updateTags(File $file, string $newTags) : void
    {
        $tags = TagMaker::toArray($newTags, $file->name);
        
        $tagsId = array();
        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagsField() => $rawTag]);
            array_push($tagsId, $tag->id);
        }
        
        $file->tags()->sync($tagsId);
    }

    private function getFile(string $fileName) : ?File
    {
        $file = File::where(Literal::nameField(), "=", $fileName)->first();

        return $file;
    }

    private const pageCap = 13;

    private $filesystem;
}

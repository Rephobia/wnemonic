<?php

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
    public static function get(string $fileName) : ?FileView
    {
        $data = self::getData($fileName);
                
        return empty($data) ? NULL : new FileView ($data);
    }

    public static function all(string $tagsString = "") : array
    {
        $cursor;
        
        if (empty($tagsString)) {
            
            $cursor = File::cursor();
            
        }
        else {
                        
            $tags = TagMaker::toArray($tagsString);

            $cursor = File::whereHas("tags", function($query) use ($tags) {
                $query->whereIn("tag", $tags);
            })->cursor();            
        }
                
        $files = array();
        foreach ($cursor as $row) {

            array_push($files, new FileView ($row));
                
        }
        
        return $files;
    }

    public static function save($file, string $tagsString) : void
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
    }

    public static function delete(string $fileName)
    {
        $data = self::getData($fileName);
        
        $path = FileInfo::hashPath($data->name);
        Storage::delete($path);
        $data->delete();
    }
    
    public static function rename(string $fileName, string $newName, string $tagsString)
    {
        $data = self::getData($fileName);
                
        $data->name = $newName;
        $data->save();
        
        $oldpath = FileInfo::hashPath($fileName);
        $newpath = FileInfo::hashPath($newName);

        $tags = TagMaker::toArray($tagsString);
        Storage::move($oldpath, $newpath);
        
        $tagsId = array();
        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagField() => $rawTag]);
            array_push($tagsId, $tag->id);
        }
        
        $data->tags()->sync($tagsId);

    }
    
    private static function getData(string $fileName) : ?File
    {
        $data = File::where(Literal::nameField(), "=", $fileName)->first();

        return $data;
    }
}

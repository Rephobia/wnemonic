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
    public static function get(string $filename) : ?FileView
    {
        $data = self::getData($filename);
                
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
        $filename = $file->getClientOriginalName();
        
        $data = new File;
        $data->name = $filename;
        $data->save();
        
        $path = FileInfo::hashPath($filename);
        Storage::putFileAs(".", $file, $path);
        
        $tags = TagMaker::toArray($tagsString);

        foreach ($tags as $rawTag) {
            $tag = Tags::firstOrCreate([Literal::tagField() => $rawTag]);
            $data->tags()->attach($tag->id);
        }
    }

    public static function delete(string $filename)
    {
        $data = self::getData($filename);
        
        $path = FileInfo::hashPath($data->name);
        Storage::delete($path);
        $data->delete();
    }
    
    public static function rename(string $filename, string $newname)
    {
        $data = self::getData($filename);
                
        $data->name = $newname;
        $data->save();
        
        $oldpath = FileInfo::hashPath($filename);
        $newpath = FileInfo::hashPath($newname);

        Storage::move($oldpath, $newpath);
    }
    
    private static function getData(string $filename) : ?File
    {
        $data = File::where(Literal::nameField(), "=", $filename)->first();

        return $data;
    }
}

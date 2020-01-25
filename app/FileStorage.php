<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class FileStorage
{
    const dir_word_size = 2;
        
    public static function nameHash(string $filename) : string
    {
        $hashed_name = self::hash($filename);
        $first_dir = substr($hashed_name, 0, self::dir_word_size);
        $second_dir = substr($hashed_name, self::dir_word_size, self::dir_word_size);
    
        $separator = "/";
        $path = join($separator, array($first_dir, $second_dir, $hashed_name));
        
        return $path;
    }
    
    public static function saveFile($file) : void
    {
        $path = self::nameHash($file->getClientOriginalName());
        
        Storage::disk("local")->putFileAs("/public", $file, $path);
    }

    public static function getExtension(string $filename) : string
    {
        $spl = new \SplFileInfo($filename);
        $extension = $spl->getExtension();
        
        return $extension;
    }
    
    public static function delete(string $filename)
    {
        $file_detail = \App\FileDetail::where("name", "=", $filename)->first();
        $path = self::nameHash($file_detail->name);
        Storage::disk("local")->delete("public/".$path);
        $file_detail->delete();
    }
    
    public static function rename(string $filename, string $newname)
    {
        $file_detail = \App\FileDetail::where("name", "=", $filename)->first();
        $file_detail->name = $newname;
        $file_detail->save();
        
        $oldpath = self::nameHash($filename);
        $newpath = self::nameHash($newname);

        Storage::disk("local")->move("public/".$oldpath, "public/".$newpath);
        return File::fromDB($file_detail);
    }
    
    
    private static function hash(string $filename) : string
    {
        $name = md5($filename);
        $extension = self::getExtension($filename);

        if (!empty($extension)) {
            
            $name .= ".".$extension;
            
        }

        return $name;
    }    
}

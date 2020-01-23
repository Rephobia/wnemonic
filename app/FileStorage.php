<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class FileStorage
{
    const dir_word_size = 2;
    
    public static function nameHash($filename) : string
    {
        $hashed_name = self::hash($filename);
        $first_dir = substr($hashed_name, 0, self::dir_word_size);
        $second_dir = substr($hashed_name, self::dir_word_size, self::dir_word_size);
    
        $separator = "/";
        $path = join($separator, array($first_dir, $second_dir, $hashed_name));
        
        return $path;
    }
    
    public static function save_file($file) : void
    {
        $path = self::nameHash($file->getClientOriginalName());
        
        Storage::putFileAs("files", $file, $path);
    }
    
    private static function hash($filename) : string
    {
        $name = md5($filename);
        $spl = new \SplFileInfo($filename);
        $extension = $spl->getExtension();
        
        if (!empty($extension)) {
            
            $name .= ".".$extension;
            
        }

        return $name;
    }
}

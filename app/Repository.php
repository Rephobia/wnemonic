<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use App\FileDetail;
use App\Literal;
use App\Utils\FileInfo;


class Repository
{
    public static function get(string $filename) : ?File
    {
        $data = self::getData($filename);
                
        return empty($data) ? NULL : new File ($data);
    }

    public static function all() : array
    {
        $files = array();
        
        foreach (FileDetail::cursor() as $row) {

            array_push($files, new File ($row));
                
        }
        
        return $files;
    }

    public static function save($file) : void
    {
        $filename = $file->getClientOriginalName();
        
        $data = new FileDetail;
        $data->name = $filename;
        $data->save();
        
        $path = FileInfo::hashPath($filename);
        Storage::putFileAs(".", $file, $path);
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
    
    private static function getData(string $filename) : FileDetail
    {
        $data = FileDetail::where(Literal::nameField(), "=", $filename)->first();

        return $data;
    }
}

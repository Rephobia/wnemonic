<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use App\FileDetail;
use App\Literal;
use App\Utils\FileInfo;


class FileStorage
{
    public static function saveFile($file) : void
    {
        $path = FileInfo::hashPath($file->getClientOriginalName());
        
        Storage::putFileAs(".", $file, $path);
    }

    public static function delete(string $filename)
    {
        $file_detail = FileDetail::where(Literal::nameField(), "=", $filename)->first();
        $path = FileInfo::hashPath($file_detail->name);
        Storage::delete($path);
        $file_detail->delete();
    }
    
    public static function rename(string $filename, string $newname)
    {
        $file_detail = FileDetail::where(Literal::nameField(), "=", $filename)->first();
        $file_detail->name = $newname;
        $file_detail->save();
        
        $oldpath = FileInfo::hashPath($filename);
        $newpath = FileInfo::hashPath($newname);

        Storage::move($oldpath, $newpath);
    }    
}

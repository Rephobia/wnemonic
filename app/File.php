<?php

namespace App;


class File
{
    public static function fromDB($data) : File
    {
        $object = new self();
        $object->data = $data;
        return $object;
    }
    
    public static function fromForm($file) : File
    {
        $object = new self();
          
        $object->data = new FileDetail;
        $object->data->name = $file->getClientOriginalName();
        $object->data->save();
      
        FileStorage::save_file($file);
        
        return $object;
    }
    
    // public static function exists(string $filename) : bool
    // {
    //     return FileDetail::where("name", "=", $filename)->exists();
    // }

    public static function get(string $filename) : ?File
    {
        $filedetail = FileDetail::where("name", "=", $filename)->first();
                
        return empty($filedetail) ? NULL : self::fromDB($filedetail);
    }
    
    public static function all()
    {
        $files = array();
        
        foreach (FileDetail::cursor() as $row) {

            array_push($files, File::fromDB($row));
                
        }
        
        return $files;
    }

    public function id() : int
    {
        return $this->data->id;
    }
    
    public function name() : string
    {
        return $this->data->name;
    }
    
    private $data;
}

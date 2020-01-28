<?php

namespace App;
use Illuminate\Support\Facades\Storage;
use App\Literal;


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
      
        FileStorage::saveFile($file);
        
        return $object;
    }

    public static function get(string $filename) : ?File
    {
        $filedetail = FileDetail::where(Literal::nameField(), "=", $filename)->first();
                
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

    public function path() : string
    {
        return FileStorage::nameHash($this->data->name);
    }
    
    public function link() : string
    {
        return Storage::disk("public")->url(self::path());
    }
    
    public function content() : string
    {
        return Storage::disk("public")->get(self::path());
    }
    
    public function type() : string
    {
        return Storage::disk("public")->mimeType(self::path());
    }
    
    private $data;
}

<?php

namespace App;

use Illuminate\Support\Facades\Storage;

use App\Utils\FileInfo;


class FileView
{
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function name() : string
    {
        return $this->data->name;
    }

    public function path() : string
    {
        return FileInfo::hashPath($this->data->name);
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
    
    public function tags() : array
    {
        if (empty($this->tags)) {
            
            foreach ($this->data->tags as $tag) {
                array_push($this->tags, $tag["tag"]);
            }
        }
        
        return $this->tags;
    }

    
    private $data;
    private $tags = array();
}

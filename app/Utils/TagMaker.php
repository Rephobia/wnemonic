<?php

namespace App\Utils;

    
class TagMaker
{
    private const delimeter = ",";
    private const spaceReplace = "_";


    public static function toArray(string $tagsString) : array
    {
        $callback = function (string $rawTag) : string
                  {
                      $trimmedTag = trim($rawTag);
        
                      return str_replace(" ", self::spaceReplace, $trimmedTag);
                  };
        
        return array_map($callback, explode(self::delimeter, $tagsString));
    }
}


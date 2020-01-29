<?php

namespace App\Utils;

class FileInfo
{    
    public static function hashPath(string $filename) : string
    {
        $nameHash = self::hash($filename);
        
        $firstDir = substr($nameHash, 0, self::dirLen);
        $secondDir = substr($nameHash, self::dirLen, self::dirLen);
    
        $separator = "/";
        $path = join($separator, array($firstDir, $secondDir, $nameHash));
        
        return $path;
    }

    public static function getExtension(string $filename) : string
    {
        $spl = new \SplFileInfo($filename);
        
        return $spl->getExtension();
    }

    private static function hash(string $filename) : string
    {
        $nameHash = md5($filename);
        $extension = self::getExtension($filename);

        if (!empty($extension)) {
            
            $nameHash .= ".".$extension;
            
        }

        return $nameHash;
    }
    
    private const dirLen = 2;
}

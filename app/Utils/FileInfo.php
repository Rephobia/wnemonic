/*
 * SPDX-License-Identifier: AGPL-3.0-or-later

 * Copyright (C) 2020 Roman Erdyakov

 * This file is part of Wnemonic. It is a tag based file manager.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/


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

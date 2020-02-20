<?php

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


namespace Tests\Feature;

use Illuminate\Http\UploadedFile;

class Seeder
{    
    /**
     * Seed a file in the testing environment
     * @return \App\FileView
     */
    public static function seed(string $fileName, string $tags,
                                $kilobytes = 1024) : \App\FileView
    {        
        $file = UploadedFile::fake()->create($fileName, $kilobytes);
                
        $repository = \App::make(\App\Repository::class);
        
        return $repository->save($file, $tags);
    }
    
    public static function seedFile(FakeFile $fakeFile) : \App\FileView
    {                        
        $repository = \App::make(\App\Repository::class);
        
        return $repository->save($fakeFile->file(), $fakeFile->tags());
    }
}

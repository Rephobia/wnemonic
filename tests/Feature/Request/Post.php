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


class Post extends \Tests\TestCase
{   
    /**
     * Checks if post request adds a file
     * @test
     * @return void
     */
    public function addFile() : void
    {
        $file = UploadedFile::fake()->create(self::fileName, 1024);

        $response = $this->post("/add",
                                array("file" => $file,
                                      "tag" => "first, second"));

        $response->assertRedirect(self::fileName);
        
        \Storage::assertExists(\App\Utils\FileInfo::hashPath(self::fileName));
    }
    
    /**
     * Checks if post request deletes a file
     * @test
     * @return void
     */
    public function deleteFile() : void
    {
        Seeder::seed(self::fileName, "first, second", \Storage::disk("local"));
        
        $response = $this->post("/delete",
                                array("name" => self::fileName));

        $response->assertRedirect("/");
        
        \Storage::assertMissing(\App\Utils\FileInfo::hashPath(self::fileName));
    }

    /**
     * Checks if post request edits a file and tags
     * @test
     * @return void
     */
    public function editFile() : void
    {
        Seeder::seed(self::fileName, "first, second", \Storage::disk("local"));

        $newName = "newName";
        $newTags = "newTag1, newTag2";
        
        $response = $this->post("/edit",
                                array(\App\Literal::nameField() => self::fileName,
                                      \App\Literal::newnameField() => $newName,
                                      \App\Literal::tagField() => $newTags
                                ));

        $response->assertRedirect($newName);

        $repository = \App::make(\App\Repository::class);
        $fileView = $repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEquals($fileView->name(), $newName);
        $this->assertEquals($fileView->tags(), \App\Utils\TagMaker::toArray($newTags));
    }

    
    private const fileName = "test_file";
}

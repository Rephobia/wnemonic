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


namespace Tests\Feature\Request;

use Tests\Feature\Seeder;
use App\Literal;
use App\Utils\TagMaker;

class EditFile extends \Tests\TestCase
{
    /**
     * Checks if post request redirects to new name after edit
     * @test
     * @return void
     */
    public function redirectAfterEdit() : void
    {
        $oldName = "old";
        $oldTags = "tag1, tag2";
        
        $newName = "new";
        $newTags = "tagNew1, tagNew2";
        
        $response = $this->send($oldName, $oldTags, $newName, $newTags);
        
        $response->assertRedirect($newName);
    }

    /**
     * Checks if post request changed name and tags
     * @test
     * @return void
     */
    public function changeNameAndTags() : void
    {
        $oldName = "old";
        $oldTags = "tag1, tag2";
        
        $newName = "new";
        $newTags = "tagNew1, tagNew2";
        
        $this->send($oldName, $oldTags, $newName, $newTags);
        $this->assertFile($newName, $newTags);
    }

    /**
     * Checks if post request changed only name
     * @test
     * @return void
     */
    public function changeName() : void
    {
        $oldName = "old";
        $oldTags = "tag1, tag2";
        
        $newName = "new";
        $newTags = $oldTags;
        
        $this->send($oldName, $oldTags, $newName, $newTags);
        $this->assertFile($newName, $newTags);
    }
    
    /**
     * Checks if post request changed only tags
     * @test
     * @return void
     */
    public function changeTags() : void
    {
        $oldName = "old";
        $oldTags = "tag1, tag2";
        
        $newName = $oldName;
        $newTags = "tagNew1, tagNew2";
        
        $this->send($oldName, $oldTags, $newName, $newTags);
        $this->assertFile($newName, $newTags);
    }
    
    /**
     * Checks if post request didn't change a name and tags
     * @test
     * @return void
     */
    public function changeNothing() : void
    {
        $oldName = "old";
        $oldTags = "tag1, tag2";
        
        $newName = $oldName;
        $newTags = $oldTags;
        
        $this->send($oldName, $oldTags, $newName, $newTags);
        $this->assertFile($newName, $newTags);
    }

    
    private function send(string $oldName, string $oldTags,
                          string $newName, string $tags)
    {
        Seeder::seed($oldName, $oldTags, \Storage::disk("local"));

        $response = $this->post("/edit",
                                array(Literal::nameField() => $oldName,
                                      Literal::newnameField() => $newName,
                                      Literal::tagField() => $tags
                                ));
        return $response;
    }
    
    private function assertFile($fileName, $tags)
    {
        $repository = \App::make(\App\Repository::class);
        $fileView = $repository->get($fileName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEquals($fileView->tags(), TagMaker::toArray($tags));
    }
}

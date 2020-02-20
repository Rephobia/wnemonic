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

use App\Literal;
use App\Utils\TagMaker;

use Tests\Feature\Seeder;
use Tests\Feature\FakeFile;


class EditFile extends \Tests\TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        
        $this->oldFile = new FakeFile;
        Seeder::seedFile($this->oldFile);
    }
    
    /**
     * Checks if post request redirects to new name after edit
     * @test
     * @return void
     */
    public function redirectAfterEdit() : void
    {
        $newName = $this->oldFile->name()."newName";
        $newTags = $this->oldFile->tags().",newTag";
        
        $response = $this->post("/edit",
                                array(Literal::nameField() => $this->oldFile->name(),
                                      Literal::newnameField() => $newName,
                                      Literal::tagField() => $newTags));
        
        $response->assertRedirect($newName);
    }

    /**
     * Checks if post request changed name and tags
     * @test
     * @return void
     */
    public function changeNameAndTags() : void
    {
        $newName = $this->oldFile->name()."newName";
        $newTags = $this->oldFile->tags().",newTag";
                 
        $this->post("/edit",
                    array(Literal::nameField() => $this->oldFile->name(),
                          Literal::newnameField() => $newName,
                          Literal::tagField() => $newTags));
        
        $this->assertFile($newName, $newTags);
    }

    /**
     * Checks if post request changed only name
     * @test
     * @return void
     */
    public function changeName() : void
    {
        $newName = $this->oldFile->name()."newName";
        $newTags = $this->oldFile->tags();
        
        $this->post("/edit",
                    array(Literal::nameField() => $this->oldFile->name(),
                          Literal::newnameField() => $newName,
                          Literal::tagField() => $newTags));
        
        $this->assertFile($newName, $newTags);
    }
    
    /**
     * Checks if post request changed only tags
     * @test
     * @return void
     */
    public function changeTags() : void
    {
        $newName = $this->oldFile->name();
        $newTags = $this->oldFile->tags().",newTag";
        
        $this->post("/edit",
                    array(Literal::nameField() => $newName,
                          Literal::newnameField() => $newName,
                          Literal::tagField() => $newTags));
        
        $this->assertFile($newName, $newTags);
    }
    
    /**
     * Checks if post request didn't change a name and tags
     * @test
     * @return void
     */
    public function changeNothing() : void
    {
        $newName = $this->oldFile->name();
        $newTags = $this->oldFile->tags();
        
        $this->post("/edit",
                    array(Literal::nameField() => $newName,
                          Literal::newnameField() => $newName,
                          Literal::tagField() => $newTags));
        
        $this->assertFile($newName, $newTags);
    }

    private function assertFile($fileName, $tags)
    {
        $repository = \App::make(\App\Repository::class);
        $fileView = $repository->get($fileName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEquals($fileView->tags(), TagMaker::toArray($tags));
    }
    
    private FakeFile $oldFile;
}

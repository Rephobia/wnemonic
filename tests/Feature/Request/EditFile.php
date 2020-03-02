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
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    use \Tests\Feature\Request\ValidateField;

    private FakeFile $fakeFile;

    public function setUp() : void
    {
        parent::setUp();
        
        $this->fakeFile = new FakeFile;
        $this->repository = \App::make(\App\Repository::class);

    }
    
    /**
     * Checks if post request redirects to new name after edit
     * @test
     * @return void
     */
    public function redirectAfterEdit() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name()."newName";
        $newTags = $this->fakeFile->tags().",newTag";
        
        $response = $this->post("/edit",
                                array(Literal::nameField() => $this->fakeFile->name(),
                                      Literal::newnameField() => $newName,
                                      Literal::tagsField() => $newTags,
                                      Literal::passField() => self::TEST_PASSWORD));
        
        $response->assertRedirect($newName);
    }

    /**
     * Checks if post request changed name and tags
     * @test
     * @return void
     */
    public function changeNameAndTags() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name()."newName";
        $newTags = $this->fakeFile->tags().",newTag";
                 
        $this->post("/edit",
                    array(Literal::nameField() => $this->fakeFile->name(),
                          Literal::newnameField() => $newName,
                          Literal::tagsField() => $newTags,
                          Literal::passField() => self::TEST_PASSWORD));
        
        $fileView = $this->repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEqualsCanonicalizing($fileView->tags(),
                                          TagMaker::toArray($newTags, $newName));
    }

    /**
     * Checks if post request changed only name
     * @test
     * @return void
     */
    public function changeName() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name()."newName";
        $newTags = $this->fakeFile->tags();
        
        $this->post("/edit",
                    array(Literal::nameField() => $this->fakeFile->name(),
                          Literal::newnameField() => $newName,
                          Literal::tagsField() => $newTags,
                          Literal::passField() => self::TEST_PASSWORD));

        $fileView = $this->repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEqualsCanonicalizing($fileView->tags(),
                                          TagMaker::toArray($newTags, $newName));
    }
    
    /**
     * Checks if post request changed only tags
     * @test
     * @return void
     */
    public function changeTags() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name();
        $newTags = $this->fakeFile->tags().",changeTag";
        $this->post("/edit",
                    array(Literal::nameField() => $newName,
                          Literal::newnameField() => $newName,
                          Literal::tagsField() => $newTags,
                          Literal::passField() => self::TEST_PASSWORD));

        $fileView = $this->repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEqualsCanonicalizing($fileView->tags(),
                                          TagMaker::toArray($newTags, $newName));
    }
    
    /**
     * Checks if post request didn't change a name and tags
     * @test
     * @return void
     */
    public function changeNothing() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name();
        $newTags = $this->fakeFile->tags();
        
        $this->post("/edit",
                    array(Literal::nameField() => $newName,
                          Literal::newnameField() => $newName,
                          Literal::tagsField() => $newTags));

        $fileView = $this->repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEqualsCanonicalizing($fileView->tags(),
                                          TagMaker::toArray($newTags, $newName));
    }

    /**
     * Checks if add request doesn't contain a new name
     * @test
     * @return void
     */
    public function oldNameMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::nameField(),
                                       NULL,
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Checks if add request doesn't contain a new name
     * @test
     * @return void
     */
    public function newNameMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::newnameField(),
                                       NULL,
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Checks if add request doesn't contain tags
     * @test
     * @return void
     */
    public function tagsMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::tagsField(),
                                       NULL,
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Checks if post request ignores a old name
     * @test
     * @return void
     */
    public function ignoreOldName() : void
    {
        Seeder::seedFile($this->fakeFile);

        $request = new \App\Http\Requests\EditFile;
        $request->merge(array(Literal::nameField() => $this->fakeFile->name()));

        $result = $this->validateField(Literal::newnameField(),
                                       $this->fakeFile->name(),
                                       $request);
        
        $this->assertTrue($result);
    }
    
    /**
     * Checks if post request tries to edit a nonexistent file
     * @test
     * @return void
     */
    public function fileNotExists() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::nameField(),
                                       $this->fakeFile->name()."notExists",
                                       $request);
        
        $this->assertFalse($result);
    }

    /**
     * Checks if post request tries to change already existing name 
     * @test
     * @return void
     */
    public function nameAlreadyExists() : void
    {
        Seeder::seedFile($this->fakeFile);

        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::newnameField(),
                                       $this->fakeFile->name(),
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Checks if edit request contains a wrong password
     * @test
     * @return void
     */
    public function wrongPassword() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $result = $this->validateField(Literal::passField(),
                                       self::TEST_PASSWORD."wrong",
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Filename is a hidden tag, if a passed tag equals a new filename,
     * the file will be without visible tags
     * @test
     * @return void
     */
    public function tagsEqualsNewName() : void
    {
        $request = new \App\Http\Requests\EditFile;
        $request->merge(array(Literal::newnameField() => $this->fakeFile->name()));

        $result = $this->validateField(Literal::tagsField(),
                                       $this->fakeFile->name(),
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Tags must be unique, becouse select by tags must be equal tags count
     * @test
     * @return void
     */
    public function uniqueTags() : void
    {
        Seeder::seedFile($this->fakeFile);

        $newName = $this->fakeFile->name()."newName";
        $newTags = $this->fakeFile->tags().",newTag";
        
        $response = $this->post("/edit",
                                array(Literal::nameField() => $this->fakeFile->name(),
                                      Literal::newnameField() => $newName,
                                      Literal::tagsField() => "{$newTags},{$newTags}",
                                      Literal::passField() => self::TEST_PASSWORD));

        $fileView = $this->repository->get($newName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEqualsCanonicalizing($fileView->tags(),
                                          TagMaker::toArray($newTags, $newName));
    }
}

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


use \App\Literal;
use \App\Utils\FileInfo;
use \App\Utils\TagMaker;

use \Tests\Feature\Seeder;
use \Tests\Feature\FakeFile;


class AddFile extends \Tests\TestCase
{
    private FakeFile $fakeFile;
    private \App\Repository $repository;

    
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    use \Tests\Feature\Request\ValidateField;
    
    public function setUp() : void
    {
        parent::setUp();
        $this->fakeFile = new FakeFile;
        $this->repository = \App::make(\App\Repository::class);
    }
    
    /**
     * Checks if post add request redirects to a new file
     * @test
     * @return void
     */
    public function redirectAfterAdd() : void
    {
        $response = $this->post("/add",
                                array(Literal::fileField() => $this->fakeFile->file(),
                                      Literal::tagsField() => $this->fakeFile->tags(),
                                      Literal::passField() => self::TEST_PASSWORD));
        
        $response->assertRedirect($this->fakeFile->name());
    }
    
    /**
     * Checks if post add request added a new file
     * @test
     * @return void
     */
    public function addFile() : void
    {
        $this->post("/add",
                    array(Literal::fileField() => $this->fakeFile->file(),
                          Literal::tagsField() => $this->fakeFile->tags(),
                          Literal::passField() => self::TEST_PASSWORD));

        $fileView = $this->repository->get($this->fakeFile->name());
        
        $this->assertNotEmpty($fileView);
        \Storage::assertExists(FileInfo::hashPath($this->fakeFile->name()));
    }
    
    /**
     * Checks if add request doesn't contain a file
     * @test
     * @return void
     */
    public function fileMissing() : void
    {
        $request = new \App\Http\Requests\NewFile;

        $result = $this->validateField(Literal::fileField(),
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
        $request = new \App\Http\Requests\NewFile;
        
        $result = $this->validateField(Literal::tagsField(),
                                       NULL,
                                       $request);
        
        $this->assertFalse($result);
    }

    /**
     * Filename is a hidden tag, if a passed tag equals filename,
     * the file will be without visible tags
     * @test
     * @return void
     */
    public function tagsEqualsName() : void
    {
        $request = new \App\Http\Requests\NewFile;
        $request->files->set(Literal::fileField(), $this->fakeFile->file());

        $result = $this->validateField(Literal::tagsField(),
                                       $this->fakeFile->name(),
                                       $request);
        
        $this->assertFalse($result);
    }
    
    /**
     * Checks if add request contains a unique file
     * @test
     * @return void
     */
    public function uniqueFile() : void
    {
        $request = new \App\Http\Requests\NewFile;
        
        Seeder::seedFile($this->fakeFile);

        $request->files->set(Literal::fileField(), $this->fakeFile->file());
        
        $result = $this->validateField(Literal::fileField(),
                                       $this->fakeFile->file(),
                                       $request);
        
        $this->assertFalse($result);
    }

    /**
     * Checks if add request contains a wrong password
     * @test
     * @return void
     */
    public function wrongPassword() : void
    {
        $request = new \App\Http\Requests\NewFile;
        
        $result = $this->validateField(Literal::passField(),
                                       self::TEST_PASSWORD."wrong",
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
        
        $this->assertEquals($fileView->tags(), TagMaker::toArray($newTags));
    }
}

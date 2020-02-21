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

use Illuminate\Support\Facades\Validator;

use App\Literal;
use App\Utils\TagMaker;

use Tests\Feature\Seeder;
use Tests\Feature\FakeFile;


class EditFile extends \Tests\TestCase
{
    private FakeFile $oldFile;

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

    /**
     * Checks if add request doesn't contain a new name
     * @test
     * @return void
     */
    public function oldNameMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $data = array(Literal::nameField() => NULL);
        $rules = array(Literal::nameField() => $request->rules()[Literal::nameField()]);

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
    }
    
    /**
     * Checks if add request doesn't contain a new name
     * @test
     * @return void
     */
    public function newNameMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $data = array(Literal::newnameField() => NULL);
        $rules = array(Literal::newnameField() => $request->rules()[Literal::newnameField()]);

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
    }
    
    /**
     * Checks if add request doesn't contain tags
     * @test
     * @return void
     */
    public function tagsMissing() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $data = array(Literal::tagField() => NULL);
        $rules = array(Literal::tagField() => $request->rules()[Literal::tagField()]);

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
    }
    
    
    /**
     * Checks if post request ignores a old name
     * @test
     * @return void
     */
    public function ignoreOldName() : void
    {
        $request = new \App\Http\Requests\EditFile;
        $request->merge(array(Literal::nameField() => $this->oldFile->name()));

        $data = array(Literal::newnameField() => $this->oldFile->name());
        $rules = array(Literal::newnameField() => $request->rules()[Literal::newnameField()]);

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }
    
    /**
     * Checks if post request tries to edit a non-existing file
     * @test
     * @return void
     */
    public function fileNonExist() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $data = array(Literal::nameField() => $this->oldFile->name()."nonExist");
        $rules = array(Literal::nameField() => $request->rules()[Literal::nameField()]);

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
    }

    /**
     * Checks if post request tries to change name to already existing name 
     * @test
     * @return void
     */
    public function nameAlreadyExists() : void
    {
        $request = new \App\Http\Requests\EditFile;
        
        $data = array(Literal::newnameField() => $this->oldFile->name());
        $rules = array(Literal::newnameField() => $request->rules()[Literal::newnameField()]);

        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
    }
    
    private function assertFile($fileName, $tags)
    {
        $repository = \App::make(\App\Repository::class);
        $fileView = $repository->get($fileName);
        
        $this->assertNotEmpty($fileView);
        $this->assertEquals($fileView->tags(), TagMaker::toArray($tags));
    }
}

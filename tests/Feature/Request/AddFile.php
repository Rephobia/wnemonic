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

use \Tests\Feature\Seeder;
use \Tests\Feature\FakeFile;


class AddFile extends \Tests\TestCase
{
    private FakeFile $fakeFile;
    
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    use \Tests\Feature\Request\ValidateField;
    
    public function setUp() : void
    {
        parent::setUp();
        $this->fakeFile = new FakeFile;
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
                                      Literal::tagsField() => $this->fakeFile->tags()));
   
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
                          Literal::tagsField() => $this->fakeFile->tags()));
   
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
     * Checks if add request contains a unique file
     * @test
     * @return void
     */
    public function uniqueFile() : void
    {
        $request = new \App\Http\Requests\NewFile;
        
        Seeder::seed($this->fakeFile->name(), $this->fakeFile->tags());
        
        $request->files->set(Literal::fileField(), $this->fakeFile->file());
        
        $result = $this->validateField(Literal::fileField(),
                                       $this->fakeFile->file(),
                                       $request);
        
        $this->assertFalse($result);
    }
}

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


class GetPages extends \Tests\TestCase
{
    /**
     * Seeds a file field in the test environment
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        
        $fileName = "test_file";
        $tags = "first tag, second tag";
        
        $this->fileView = Seeder::seed($fileName, $tags, \Storage::fake("public"));
    }
    
    /**
     * Checks if home page exists
     * @test
     * @return void
     */
    public function homeExists() : void
    {
        $this->get("/")
             ->assertStatus(200);
    }

    /**
     * Checks if add page exists
     * @test
     * @return void
     */
    public function addExists() : void
    {
        $this->get("/add")
             ->assertStatus(200);
    }

    /**
     * Checks if a file page exists
     * @test
     * @return void
     */
    public function fileViewExists() : void
    {        
        $this->get("/".$this->fileView->name())
             ->assertStatus(200);
    }

    /**
     * Checks if a file edit page exists
     * @test
     * @return void
     */
    public function editExists() : void
    {        
        $this->get("/edit/".$this->fileView->name())
             ->assertStatus(200);
    }

    /**
     * Checks if tags page exist
     * @test
     * @return void
     */
    public function tagsExists() : void
    {        
        $this->get("/tag/".$this->fileView->tagsString())
             ->assertStatus(200);
    }
    
    private $fileView;
}

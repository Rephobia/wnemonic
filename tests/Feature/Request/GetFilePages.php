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


class GetFilePages extends \Tests\TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private $fakeFile;

    /**
     * Seeds a file field in the test environment
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        
        $this->fakeFile = new FakeFile;
        Seeder::seedFile($this->fakeFile);
    }

    /**
     * Checks if a file page exists
     * @test
     * @return void
     */
    public function fileViewExists() : void
    {        
        $this->get("/".$this->fakeFile->name())
             ->assertStatus(200);
    }

    /**
     * Checks if a file page doesn't exist
     * @test
     * @return void
     */
    public function fileViewNotExists() : void
    {        
        $this->get("/".$this->fakeFile->name()."notExists")
             ->assertStatus(404);
    }

    /**
     * Checks if a file edit page exists
     * @test
     * @return void
     */
    public function editExists() : void
    {        
        $this->get("/edit/".$this->fakeFile->name())
             ->assertStatus(200);
    }
    
    /**
     * Checks if a file edit page doesn't exist
     * @test
     * @return void
     */
    public function editNotExists() : void
    {        
        $this->get("/edit/".$this->fakeFile->name()."notExists")
             ->assertStatus(404);
    }

    /**
     * Checks if search page exist
     * @test
     * @return void
     */
    public function searchExists() : void
    {        
        $this->get("/search/".$this->fakeFile->tags())
             ->assertStatus(200);
    }
    /**
     * Checks if search page doesn't exist, but empty page shows
     * @test
     * @return void
     */
    public function searchNotExists() : void
    {        
        $this->get("/search/notExistsTag")
             ->assertStatus(200);
    }
}

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

use Illuminate\Http\UploadedFile;
use \App\Literal;
use \App\Utils\FileInfo;


class AddFile extends \Tests\TestCase
{
    /**
     * Checks if post add request redirects to a new file
     * @test
     * @return void
     */
    public function redirectAfterAdd() : void
    {
        $fileName = "newFile";
        $tags = "tag1, tag2";

        $response = $this->send($fileName, $tags);

        $response->assertRedirect($fileName);
    }
    
    /**
     * Checks if post add request added a new file
     * @test
     * @return void
     */
    public function addFile() : void
    {
        $fileName = "newFile";
        $tags = "tag1, tag2";

        $this->send($fileName, $tags);
        
        \Storage::assertExists(FileInfo::hashPath($fileName));
    }
    
    private function send($fileName, $tags)
    {
        $file = UploadedFile::fake()->create($fileName, 1024);

        $response = $this->post("/add",
                                array(Literal::fileField() => $file,
                                      Literal::tagField() => $tags));
        return $response;
    }
}

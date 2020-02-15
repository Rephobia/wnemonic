<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;


class Repository extends \Tests\TestCase
{    
    /**
     * Check if a file was written to disk
     * @test
     * @return void
     */
    public function writeFile() : void
    {
        $kilobytes = 1024;
        
        $file = UploadedFile::fake()->create("testfiles", $kilobytes);
        $repository = $this->app->make(\App\Repository::class);
        $fileView = $repository->save($file, "tag");

        \Storage::assertExists($fileView->path());
    }
}

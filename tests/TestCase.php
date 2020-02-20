<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    public function setUp() : void
    {
        parent::setUp();
        \Artisan::call('migrate');
        \Storage::fake("local");
        \Storage::fake("public");
    }
}

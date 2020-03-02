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

        $hashed = (new \App\Console\Commands\Password)->hash(self::TEST_PASSWORD);

        $_ENV[\App\Literal::passwordKey()] = $hashed;

    }

    protected const TEST_PASSWORD = "testPass";
}

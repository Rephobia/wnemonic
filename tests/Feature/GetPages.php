<?php

namespace Tests\Feature;


class GetPages extends \Tests\TestCase
{    
    /**
     * Checks if home page exists
     * @test
     * @return void
     */
    public function homeExists() : void
    {
        $response = $this->get("/");
        $response->assertStatus(200);
    }

    /**
     * Checks if add page exists
     * @test
     * @return void
     */
    public function addExists() : void
    {
        $response = $this->get("/add");
        $response->assertStatus(200);
    }
}

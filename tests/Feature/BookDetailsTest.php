<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookDetailsTest extends TestCase
{
    /**
     * GET request for book details for a real, existing ISBN
     *
     * @return void
     */
    public function testExistingISBN()
    {
        $response = $this->call('GET', '/details', ["ISBN"=>"9780316045698"]);

        //$this->assertViewHas('book');
        //$response = $this->get('/details/?ISBN=9780316045698');

        $response->assertStatus(200);
    }

    /**
     * GET request for book details for a fake ISBN
     *
     * @return void
     */
    public function testCorruptedISBN()
    {
        $response = $this->get('/details', ["ISBN"=>"000"]);

        $response->assertStatus(500);
    }
}

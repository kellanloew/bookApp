<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * empty string for search term
     *
     * @return void
     */
    public function testBlankSearch()
    {
        $response = $this->call('POST', 'search', array(
            '_token' => csrf_token(),
            "keyword" => ""
        ));
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('term');
        $response->assertViewHas('foundBooks');
    }

    /**
     * search terms with nonalphanumeric characters
     *
     * @return void
     */
    public function testNonAplhanumericSearch(){
        $response = $this->call('POST', 'search', array(
            '_token' => csrf_token(),
            "keyword" => "bob+joe"
        ));
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('term');
        $response->assertViewHas('foundBooks');

    }
}

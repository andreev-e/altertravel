<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class CatalogTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_locations()
    {
        $response = $this->get('/locations');
        $response->assertStatus(200);
    }

    public function test_location()
    {
        $response = $this->get('/locations/rossiya');
        $response->assertStatus(200);
    }

    public function test_tag()
    {
        $response = $this->get('/tag/avtomobil');
        $response->assertStatus(200);
    }

    public function test_category()
    {
        $response = $this->get('/category/arxitektura');
        $response->assertStatus(200);
    }

    public function test_tag_location()
    {
        $response = $this->get('/tag/avtomobil/azerbaidzan');
        $response->assertStatus(200);
    }

    public function test_category_location()
    {
        $response = $this->get('/category/arxitektura/armeniya');
        $response->assertStatus(200);
    }

    public function test_secure()
    {
        $response = $this->get('/secure');
        $response->assertStatus(200);
        
    }


}

<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_travels_list_returns_pagination_correct()
    {
        Travel::factory(16)->create(['is_public' => true]);
        $response = $this->get('/api/travels');
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travels_list_shows_only_public_records()
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        $response = $this->get('/api/travels');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', $travel->name);
    }
}

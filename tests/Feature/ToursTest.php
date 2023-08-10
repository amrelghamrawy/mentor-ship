<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToursTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_returned_by_travel_slug_returns_correct_tours()
    {
        $travel = Travel::factory()->create();

        $Tour = Tour::factory()->create([
            'travel_id' => $travel->id,
        ]);

        $response = $this->get("/api/travel/$travel->slug/tours");
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $Tour->id]);
    }

    public function test_tour_shows_price_correctly()
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);

        $response = $this->get("/api/travel/$travel->slug/tours");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_returns_pagination()
    {
        $travel = Travel::factory()->create();
        Tour::factory(16)->create([
            'travel_id' => $travel->id,
        ]);
        $response = $this->get("/api/travel/$travel->slug/tours");
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tours_list_filterd_by_dateFrom_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour1 = Tour::factory()->create([
            'starting_date' => '2023-06-01',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'starting_date' => '2023-06-15',
            'travel_id' => $travel->id,
        ]);
        $Tour3 = Tour::factory()->create([
            'starting_date' => '2023-05-30',
            'travel_id' => $travel->id,
        ]);
        $dateFrom = '2023-06-01';

        $response = $this->get("/api/travel/$travel->slug/tours?dateFrom=$dateFrom");
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $Tour1->id]);
        $response->assertJsonFragment(['id' => $Tour2->id]);
        $response->assertJsonMissing(['id' => $Tour3->id]);
    }

    public function test_tours_list_filterd_by_dateTo_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour1 = Tour::factory()->create([
            'ending_date' => '2023-06-01',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'ending_date' => '2023-06-15',
            'travel_id' => $travel->id,
        ]);
        $Tour3 = Tour::factory()->create([
            'ending_date' => '2023-05-30',
            'travel_id' => $travel->id,
        ]);
        $dateTo = '2023-06-01';

        $response = $this->get("/api/travel/$travel->slug/tours?dateTo=$dateTo");
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $Tour1->id]);
        $response->assertJsonFragment(['id' => $Tour3->id]);
        $response->assertJsonMissing(['id' => $Tour2->id]);
    }

    public function test_tours_list_filterd_by_priceFrom_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour1 = Tour::factory()->create([
            'price' => '3000',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);
        $Tour3 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $price = 4000;

        $response = $this->get("/api/travel/$travel->slug/tours?priceFrom=$price");
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $Tour2->id]);
        $response->assertJsonFragment(['id' => $Tour3->id]);
        $response->assertJsonMissing(['id' => $Tour1->id]);
    }

    public function test_tours_list_filterd_by_priceTo_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour1 = Tour::factory()->create([
            'price' => '3000',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);
        $Tour3 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $price = 4000;

        $response = $this->get("/api/travel/$travel->slug/tours?priceTo=$price");
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $Tour2->id]);
        $response->assertJsonFragment(['id' => $Tour1->id]);
        $response->assertJsonMissing(['id' => $Tour3->id]);
    }

    public function test_tours_list_ordered_by_price_asc_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour1 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);
        $Tour3 = Tour::factory()->create([
            'price' => '300',
            'travel_id' => $travel->id,
        ]);
        $response = $this->get("/api/travel/$travel->slug/tours?sortBy=price&sortOrder=asc");
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.id', $Tour3->id);
        $response->assertJsonPath('data.1.id', $Tour2->id);
        $response->assertJsonPath('data.2.id', $Tour1->id);

    }

    public function test_tours_list_ordered_by_price_desc_correctly()
    {
        $travel = Travel::factory()->create();
        $Tour3 = Tour::factory()->create([
            'price' => '300',
            'travel_id' => $travel->id,
        ]);
        $Tour1 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);

        $response = $this->get("/api/travel/$travel->slug/tours?sortBy=price&sortOrder=desc");
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.id', $Tour1->id);
        $response->assertJsonPath('data.1.id', $Tour2->id);
        $response->assertJsonPath('data.2.id', $Tour3->id);
    }

    public function test_tours_list_ordered_by_price_dateFrom_and_dateTo_parameter_validation_error()
    {
        $travel = Travel::factory()->create();
        $Tour3 = Tour::factory()->create([
            'price' => '300',
            'travel_id' => $travel->id,
        ]);
        $Tour1 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get("/api/travel/$travel->slug/tours?dateFrom=abcd&dateTo=abcd");
        $response->assertStatus(422);
        $response->assertJsonFragment(['dateFrom' => ['The date from field must be a valid date.']]);
        $response->assertJsonFragment(['dateTo' => ['The date to field must be a valid date.']]);
    }

    public function test_tours_list_ordered_by_price_priceFrom_and_priceTo_parameter_validation_error()
    {
        $travel = Travel::factory()->create();
        $Tour3 = Tour::factory()->create([
            'price' => '300',
            'travel_id' => $travel->id,
        ]);
        $Tour1 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get("/api/travel/$travel->slug/tours?priceFrom=abcd&priceTo=abcd");
        $response->assertStatus(422);
        $response->assertJsonFragment(['priceFrom' => ['The price from field must be a number.']]);
        $response->assertJsonFragment(['priceTo' => ['The price to field must be a number.']]);
    }

    public function test_tours_list_ordered_by_sortBy_sortOrder_parameter_validation_error()
    {
        $travel = Travel::factory()->create();
        $Tour3 = Tour::factory()->create([
            'price' => '300',
            'travel_id' => $travel->id,
        ]);
        $Tour1 = Tour::factory()->create([
            'price' => '4500',
            'travel_id' => $travel->id,
        ]);
        $Tour2 = Tour::factory()->create([
            'price' => '4000',
            'travel_id' => $travel->id,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get("/api/travel/$travel->slug/tours?sortBy=abcd&sortOrder=abcd");
        $response->assertStatus(422);
        $response->assertJsonFragment(['sortBy' => ["the 'sortBy' parameter by should be 'price'"]]);
        $response->assertJsonFragment(['sortOrder' => ["the 'sortorder' parameter by should be 'asc' or 'desc'"]]);
    }
}

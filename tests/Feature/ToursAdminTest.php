<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToursAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_unautenticated_user_cannot_store_tours(): void
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson("/api/admin/travels/$travel->id/tours", [
            'name' => 'test_name',
            'starting_date' => '2023-07-10',
            'ending_date' => '2023-07-15',
            'price' => 99.99,
        ]);
        $response->assertStatus(401);
    }

    public function test_non_admin_users_cannot_store_tours()
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson("/api/admin/travels/$travel->id/tours", [
            'name' => 'test_name',
            'starting_date' => '2023-07-10',
            'ending_date' => '2023-07-15',
            'price' => 99.99,
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_cannot_create_Tour_with_invalid_data()
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $role = Role::where('name', 'admin')->first();

        $user->roles()->attach($role->id);

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/admin/travels/$travel->id/tours", [
            'name' => 'test_name',
            'starting_date' => now()->toDateString(),
            'ending_date' => now()->addDays(rand(1, 9))->toDateString(),
            'price' => 'wrong data',
        ]);
        $response->assertStatus(422);

    }

    public function test_admin_can_create_Tour_with_valid_date()
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $role = Role::where('name', 'admin')->first();

        $user->roles()->attach($role->id);

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/admin/travels/$travel->id/tours", [
            'name' => 'test_name',
            'starting_date' => '2023-07-10',
            'ending_date' => '2023-07-15',
            'price' => 99.99,
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'test_name']);
    }
}

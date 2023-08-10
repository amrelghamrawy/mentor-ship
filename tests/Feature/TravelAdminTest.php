<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelAdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_public_user_cannot_create_travel()
    {
        $response = $this->postJson('/api/admin/travels', [
            'name' => 'travel test 1 ',
            'is_public' => 1,
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda voluptatum animi iste nostrum. Qui inventore quas illo sunt amet aspernatur repellendus corporis quaerat tempore laboriosam, aliquid rem doloribus vero est.',
            'number_of_days' => 8,
        ]);
        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_create_travel()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/admin/travels', [
            'name' => 'travel test 1 ',
            'is_public' => 1,
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda voluptatum animi iste nostrum. Qui inventore quas illo sunt amet aspernatur repellendus corporis quaerat tempore laboriosam, aliquid rem doloribus vero est.',
            'number_of_days' => 8,
        ]);
        $response->assertStatus(403);
    }

    public function test_travel_insert_validation()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/admin/travels', [
            'name' => 'travel test 1 ',

        ]);
        $response->assertStatus(422);
    }

    public function test_admin_can_create_travel_with_valid_date()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/admin/travels', [
            'name' => 'travel test 1 ',
            'is_public' => 1,
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda voluptatum animi iste nostrum. Qui inventore quas illo sunt amet aspernatur repellendus corporis quaerat tempore laboriosam, aliquid rem doloribus vero est.',
            'number_of_days' => 8,

        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'travel test 1']);
    }

    public function test_unauthenticated_user_cannot_update_travel()
    {
        $travel = Travel::factory()->create();
        $response = $this->putJson('/api/admin/travels/'.$travel->id, [
            'name' => 'travel test 1 ',
            'is_public' => 1,
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda voluptatum animi iste nostrum. Qui inventore quas illo sunt amet aspernatur repellendus corporis quaerat tempore laboriosam, aliquid rem doloribus vero est.',
            'number_of_days' => 8,

        ]);
        $response->assertStatus(401);
    }

    public function test_editor_can_update_travel()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/admin/travels/'.$travel->id, [
            'name' => 'travel test 1 ',
            'is_public' => 1,
            'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda voluptatum animi iste nostrum. Qui inventore quas illo sunt amet aspernatur repellendus corporis quaerat tempore laboriosam, aliquid rem doloribus vero est.',
            'number_of_days' => 8,

        ]);
        $response->assertStatus(200);
    }

    public function test_editor_cannot_update_travel_with_invaled_data()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/admin/travels/'.$travel->id, [
            'name' => 'travel test 1 ',
        ]);
        $response->assertStatus(422);
    }
}

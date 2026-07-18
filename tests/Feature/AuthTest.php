<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        $role = Role::create([
            'name'        => 'admin',
            'label'       => 'Administrator',
            'description' => 'Full access',
        ]);

        return User::create([
            'first_name' => 'Admin',
            'last_name'  => 'Test',
            'email'      => 'admin@test.com',
            'password'   => bcrypt('password'),
            'role_id'    => $role->id,
            'is_active'  => true,
        ]);
    }

    public function test_login_fails_with_empty_body(): void
    {
        $this->postJson('/api/login', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $this->createAdminUser();
        $this->postJson('/api/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ])->assertStatus(401);
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $this->createAdminUser();
        $this->postJson('/api/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ])->assertStatus(200)
          ->assertJsonStructure([
              'access_token',
              'token_type',
              'user' => ['id', 'email', 'first_name'],
          ]);
    }

    public function test_unauthenticated_cannot_access_students(): void
    {
        $this->getJson('/api/students')->assertStatus(401);
    }

    public function test_unauthenticated_cannot_access_dashboard(): void
    {
        $this->getJson('/api/dashboard')->assertStatus(401);
    }

    public function test_authenticated_user_can_access_students(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user, 'sanctum')
             ->getJson('/api/students')
             ->assertStatus(200);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user, 'sanctum')
             ->getJson('/api/dashboard')
             ->assertStatus(200)
             ->assertJsonStructure(['success', 'data']);
    }

    public function test_logout_requires_authentication(): void
    {
        $this->postJson('/api/logout')->assertStatus(401);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user, 'sanctum')
             ->postJson('/api/logout')
             ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaunchBridgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_startup_can_post_project_and_graduate_can_apply_once(): void
    {
        $startup = User::factory()->create(['role' => 'startup']);
        $graduate = User::factory()->create(['role' => 'graduate']);
        $payload = [
            'title' => 'Build a startup dashboard',
            'description' => 'Create an internal dashboard for our small business.',
            'skills' => ['Laravel', 'MySQL'],
            'compensation' => 'BDT 3000 fixed',
        ];
        $this->actingAs($startup)->postJson('/api/projects', $payload)->assertCreated();
        $this->actingAs($graduate)->postJson('/api/projects', $payload)->assertForbidden();
        $apply = ['message' => 'I have experience with Laravel and MySQL.'];
        $this->actingAs($graduate)->postJson('/api/projects/1/apply', $apply)->assertCreated();
        $this->actingAs($graduate)->postJson('/api/projects/1/apply', $apply)->assertStatus(409);
    }

    public function test_unauthed_users_cannot_post_projects(): void
    {
        $this->postJson('/api/projects', [])->assertUnauthorized();
    }
}

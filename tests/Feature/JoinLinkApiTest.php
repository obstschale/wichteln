<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class JoinLinkApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    public function testAdminCanGenerateJoinLink(): void
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin, 'api')->postJson(
            "/api/v1/wichtelgroups/{$group->id}/join-link",
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['join_url', 'join_token']);

        $group->refresh();
        $this->assertNotNull($group->join_token);
        $this->assertEquals(32, strlen($group->join_token));
    }

    public function testGenerateJoinLinkReturnsExistingToken(): void
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'existing-token-1234567890123',
        ]);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin, 'api')->postJson(
            "/api/v1/wichtelgroups/{$group->id}/join-link",
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['join_token' => 'existing-token-1234567890123']);
    }

    public function testNonAdminCannotGenerateJoinLink(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($member, 'api')->postJson(
            "/api/v1/wichtelgroups/{$group->id}/join-link",
        );

        $response->assertStatus(403);
    }

    public function testAdminCanRevokeJoinLink(): void
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'token-to-revoke-123456789',
        ]);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin, 'api')->deleteJson(
            "/api/v1/wichtelgroups/{$group->id}/join-link",
        );

        $response->assertStatus(204);

        $group->refresh();
        $this->assertNull($group->join_token);
    }

    public function testNonAdminCannotRevokeJoinLink(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'token-cannot-revoke-12345',
        ]);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($member, 'api')->deleteJson(
            "/api/v1/wichtelgroups/{$group->id}/join-link",
        );

        $response->assertStatus(403);

        $group->refresh();
        $this->assertNotNull($group->join_token);
    }

    public function testShowGroupIncludesJoinUrl(): void
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'show-group-token-12345678',
        ]);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin, 'api')->getJson(
            "/api/v1/wichtelgroups/{$group->id}",
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['join_url']);
        $this->assertStringContainsString('show-group-token-12345678', $response->json('join_url'));
    }

    public function testShowGroupReturnsNullJoinUrlWhenNoToken(): void
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => null,
        ]);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin, 'api')->getJson(
            "/api/v1/wichtelgroups/{$group->id}",
        );

        $response->assertStatus(200);
        $response->assertJson(['join_url' => null]);
    }
}

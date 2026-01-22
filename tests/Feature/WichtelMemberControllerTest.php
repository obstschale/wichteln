<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\Mail\ApproveWichtelMember;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WichtelMemberControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    public function testAdminCanChangeStatusToApproved(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'invited',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin, 'api')->putJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$member->id}",
            ['status' => 'approved'],
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
            'status' => 'approved',
        ]);
    }

    public function testAdminCanChangeStatusToInvited(): void
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

        $response = $this->actingAs($admin, 'api')->putJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$member->id}",
            ['status' => 'invited'],
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
            'status' => 'invited',
        ]);
    }

    public function testNonAdminCannotChangeStatus(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $otherMember = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $group->users()->attach($otherMember, [
            'status' => 'invited',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($member, 'api')->putJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$otherMember->id}",
            ['status' => 'approved'],
        );

        $response->assertStatus(403);
    }

    public function testAdminCanResendInvitation(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'invited',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin, 'api')->postJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$member->id}/resend-invitation",
        );

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Einladung wurde erneut versendet.']);

        Mail::assertQueued(ApproveWichtelMember::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });
    }

    public function testNonAdminCannotResendInvitation(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $member = User::factory()->create();
        $otherMember = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $group->users()->attach($otherMember, [
            'status' => 'invited',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($member, 'api')->postJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$otherMember->id}/resend-invitation",
        );

        $response->assertStatus(403);
        Mail::assertNotQueued(ApproveWichtelMember::class);
    }

    public function testInvalidStatusIsRejected(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['status' => 'created']);

        $group->users()->attach($admin, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $group->users()->attach($member, [
            'status' => 'invited',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin, 'api')->putJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$member->id}",
            ['status' => 'invalid_status'],
        );

        $response->assertStatus(422);
    }

    public function testMemberCanUpdateOwnWishlist(): void
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
            'wishlist' => null,
        ]);

        $response = $this->actingAs($member, 'api')->putJson(
            "/api/v1/wichtelgroups/{$group->id}/wichtelmembers/{$member->id}",
            ['wishlist' => 'Ich wünsche mir ein Buch'],
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
            'wishlist' => 'Ich wünsche mir ein Buch',
        ]);
    }
}

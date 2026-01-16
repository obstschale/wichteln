<?php

namespace Tests\Unit;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Unit tests for the User model.
 * Tests cover group membership, pivot data, and status/wishlist operations.
 */
class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    /** @test belongsToGroup() returns true when user is a member */
    public function testBelongsToGroupReturnsTrueWhenUserIsMember()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'approved', 'is_admin' => false]);

        $this->assertTrue($user->belongsToGroup($group));
    }

    /** @test belongsToGroup() returns false when user is not a member */
    public function testBelongsToGroupReturnsFalseWhenUserIsNotMember()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $this->assertFalse($user->belongsToGroup($group));
    }

    /** @test pivotDataFor() returns correct pivot table data */
    public function testPivotDataForReturnsCorrectData()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, [
            'status' => 'approved',
            'is_admin' => true,
            'wishlist' => 'Test wishlist',
            'token' => 'abc123',
        ]);

        $pivotData = $user->pivotDataFor($group);

        $this->assertEquals('approved', $pivotData->status);
        $this->assertEquals(1, $pivotData->is_admin);
        $this->assertEquals('Test wishlist', $pivotData->wishlist);
        $this->assertEquals('abc123', $pivotData->token);
    }

    /** @test isAdminInGroup() returns true for admin users */
    public function testIsAdminInGroupReturnsTrueForAdmin()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'approved', 'is_admin' => true]);

        $this->assertTrue((bool) $user->isAdminInGroup($group));
    }

    /** @test isAdminInGroup() returns false for non-admin users */
    public function testIsAdminInGroupReturnsFalseForNonAdmin()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'approved', 'is_admin' => false]);

        $this->assertFalse((bool) $user->isAdminInGroup($group));
    }

    /** @test isAdminInGroup() returns false when user is not a group member */
    public function testIsAdminInGroupReturnsFalseWhenNotMember()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $this->assertFalse($user->isAdminInGroup($group));
    }

    /** @test status() returns correct pivot status for group */
    public function testStatusReturnsCorrectStatus()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'invited', 'is_admin' => false]);

        $this->assertEquals('invited', $user->status($group));
    }

    /** @test saveStatus() updates the user's pivot status */
    public function testSaveStatusUpdatesStatus()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'invited', 'is_admin' => false]);

        $user->saveStatus($group, 'approved');

        $this->assertEquals('approved', $user->status($group));
    }

    /** @test wishlist() returns correct pivot wishlist for group */
    public function testWishlistReturnsCorrectWishlist()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, [
            'status' => 'approved',
            'is_admin' => false,
            'wishlist' => 'Nintendo Switch',
        ]);

        $this->assertEquals('Nintendo Switch', $user->wishlist($group));
    }

    /** @test saveWishlist() updates the user's pivot wishlist */
    public function testSaveWishlistUpdatesWishlist()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, [
            'status' => 'approved',
            'is_admin' => false,
            'wishlist' => 'Old wishlist',
        ]);

        $user->saveWishlist($group, 'New wishlist');

        $this->assertEquals('New wishlist', $user->wishlist($group));
    }

    /** @test approveToken() returns the user's approval token for group */
    public function testApproveTokenReturnsToken()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, [
            'status' => 'invited',
            'is_admin' => false,
            'token' => 'secret-token-123',
        ]);

        $this->assertEquals('secret-token-123', $user->approveToken($group));
    }

    /** @test saveApproveToken() updates the user's approval token */
    public function testSaveApproveTokenUpdatesToken()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, [
            'status' => 'invited',
            'is_admin' => false,
            'token' => 'old-token',
        ]);

        $user->saveApproveToken($group, 'new-token');

        $this->assertEquals('new-token', $user->approveToken($group));
    }

    /** @test saveBuddy() updates the user's assigned buddy_id */
    public function testSaveBuddyUpdatesBuddyId()
    {
        $user = User::factory()->create();
        $buddy = User::factory()->create();
        $group = Group::factory()->create();

        $group->users()->attach($user, ['status' => 'approved', 'is_admin' => false]);
        $group->users()->attach($buddy, ['status' => 'approved', 'is_admin' => false]);

        $user->saveBuddy($group, $buddy->id);

        $this->assertEquals($buddy->id, $user->groups()->where('group_id', $group->id)->first()->pivot->buddy_id);
    }
}

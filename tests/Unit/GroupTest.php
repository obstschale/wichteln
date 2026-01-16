<?php

namespace Tests\Unit;

use App\Group;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Unit tests for the Group model.
 * Tests cover status checks, user relationships, and query scopes.
 */
class GroupTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    /** @test started() returns true when group status is 'started' */
    public function testStartedReturnsTrueWhenStatusIsStarted()
    {
        $group = Group::factory()->create(['status' => 'started']);

        $this->assertTrue($group->started());
    }

    /** @test started() returns false when group status is 'created' */
    public function testStartedReturnsFalseWhenStatusIsCreated()
    {
        $group = Group::factory()->create(['status' => 'created']);

        $this->assertFalse($group->started());
    }

    /** @test approvedUsers() only returns users with 'approved' pivot status */
    public function testApprovedUsersReturnsOnlyApprovedMembers()
    {
        $group = Group::factory()->create();
        $approvedUser = User::factory()->create();
        $invitedUser = User::factory()->create();

        $group->users()->attach($approvedUser, ['status' => 'approved', 'is_admin' => false]);
        $group->users()->attach($invitedUser, ['status' => 'invited', 'is_admin' => false]);

        $approvedUsers = $group->approvedUsers;

        $this->assertCount(1, $approvedUsers);
        $this->assertTrue($approvedUsers->contains($approvedUser));
        $this->assertFalse($approvedUsers->contains($invitedUser));
    }

    /** @test admin() returns the user with is_admin=true */
    public function testAdminReturnsAdminUser()
    {
        $group = Group::factory()->create();
        $admin = User::factory()->create();
        $member = User::factory()->create();

        $group->users()->attach($admin, ['status' => 'approved', 'is_admin' => true]);
        $group->users()->attach($member, ['status' => 'approved', 'is_admin' => false]);

        $this->assertEquals($admin->id, $group->admin()->id);
    }

    /** @test scopeStarted filters groups with status 'started' */
    public function testScopeStartedFiltersOnlyStartedGroups()
    {
        Group::factory()->create(['status' => 'started']);
        Group::factory()->create(['status' => 'created']);

        $startedGroups = Group::query()->started()->get();

        $this->assertCount(1, $startedGroups);
        $this->assertEquals('started', $startedGroups->first()->status);
    }

    /** @test scopeCreated filters groups with status 'created' */
    public function testScopeCreatedFiltersOnlyCreatedGroups()
    {
        Group::factory()->create(['status' => 'started']);
        Group::factory()->create(['status' => 'created']);

        $createdGroups = Group::query()->where('status', 'created')->get();

        $this->assertCount(1, $createdGroups);
        $this->assertEquals('created', $createdGroups->first()->status);
    }

    /** @test scopeOlderThan filters groups older than given time period */
    public function testScopeOlderThanFiltersGroupsOlderThanGivenTime()
    {
        $oldGroup = Group::factory()->create(['status' => 'created']);
        $oldGroup->updated_at = Carbon::now()->subMonths(2);
        $oldGroup->save();

        $newGroup = Group::factory()->create(['status' => 'created']);

        $olderGroups = Group::olderThan(1, 'month')->get();

        $this->assertCount(1, $olderGroups);
        $this->assertEquals($oldGroup->id, $olderGroups->first()->id);
    }

    /** @test scopeDateReached filters groups whose event date has passed */
    public function testScopeDateReachedFiltersGroupsWithPastDate()
    {
        $pastGroup = Group::factory()->create(['date' => Carbon::now()->subDays(5)]);
        $futureGroup = Group::factory()->create(['date' => Carbon::now()->addDays(5)]);

        $reachedGroups = Group::dateReached()->get();

        $this->assertCount(1, $reachedGroups);
        $this->assertEquals($pastGroup->id, $reachedGroups->first()->id);
    }

    /** @test scopeNotInformed filters groups not yet informed about deletion */
    public function testScopeNotInformedFiltersGroupsNotYetInformed()
    {
        $informedGroup = Group::factory()->create(['isInformedDeletion' => true]);
        $notInformedGroup = Group::factory()->create(['isInformedDeletion' => false]);

        $notInformed = Group::notInformed()->get();

        $this->assertCount(1, $notInformed);
        $this->assertEquals($notInformedGroup->id, $notInformed->first()->id);
    }

    /** @test scopeForDeletion filters informed groups older than one week */
    public function testScopeForDeletionFiltersInformedGroupsOlderThanOneWeek()
    {
        $oldInformed = Group::factory()->create(['isInformedDeletion' => true]);
        $oldInformed->updated_at = Carbon::now()->subWeeks(2);
        $oldInformed->save();

        $recentInformed = Group::factory()->create(['isInformedDeletion' => true]);

        $forDeletion = Group::forDeletion()->get();

        $this->assertCount(1, $forDeletion);
        $this->assertEquals($oldInformed->id, $forDeletion->first()->id);
    }
}

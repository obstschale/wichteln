<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DrawAssignmentsTest extends TestCase
{
    use DatabaseMigrations;

    private Group $group;
    private Group $otherGroup;
    private User $admin;
    private User $member;
    private User $recipient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->otherGroup = Group::factory()->create();
        $this->group = Group::factory()->create(['status' => 'started']);
        $this->admin = User::factory()->create(['name' => 'Anna']);
        $this->member = User::factory()->create(['name' => 'Ben']);
        $this->recipient = User::factory()->create(['name' => 'Clara']);

        $this->otherGroup->users()->attach($this->member, ['status' => 'approved', 'is_admin' => true]);
        $this->group
            ->users()
            ->attach([
                $this->admin->id => ['status' => 'approved', 'is_admin' => true, 'buddy_id' => $this->member->id],
                $this->member->id => ['status' => 'approved', 'is_admin' => false, 'buddy_id' => $this->recipient->id],
                $this->recipient->id => [
                    'status' => 'approved',
                    'is_admin' => false,
                    'buddy_id' => $this->admin->id,
                ],
            ]);
        $this->recipient->saveWishlist($this->group, 'Ein Buch');
    }

    private function endpoint(): string
    {
        return "/api/v1/wichtelgroups/{$this->group->id}/assignments";
    }

    public function testAdminReceivesOnlyDrawnPairsWithDistinctGiverIdsForMatchingNames(): void
    {
        $this->recipient->update(['name' => 'Anna']);
        foreach (['invited', 'declined'] as $status) {
            $this->group->users()->attach(User::factory()->create(), ['status' => $status, 'is_admin' => false]);
        }

        $this
            ->actingAs($this->admin, 'api')
            ->getJson($this->endpoint())
            ->assertOk()
            ->assertExactJson([
                ['giver_id' => $this->admin->id, 'giver' => 'Anna', 'recipient' => 'Ben'],
                ['giver_id' => $this->member->id, 'giver' => 'Ben', 'recipient' => 'Anna'],
                ['giver_id' => $this->recipient->id, 'giver' => 'Anna', 'recipient' => 'Anna'],
            ]);
    }

    public function testOnlyAnAdminOfThisGroupHasAccess(): void
    {
        $this->getJson($this->endpoint())->assertUnauthorized();
        $this->actingAs($this->recipient, 'api')->getJson($this->endpoint())->assertForbidden();
        $this->actingAs($this->member, 'api')->getJson($this->endpoint())->assertForbidden();
    }

    public function testUnavailableDrawNeverReturnsPartialPairs(): void
    {
        $this->actingAs($this->admin, 'api');
        $this->group->update(['status' => 'created']);
        $this->getJson($this->endpoint())->assertConflict()->assertDontSee('"giver"', false);

        $this->group->update(['status' => 'started']);
        $this->member->saveBuddy($this->group, null);
        $this->getJson($this->endpoint())->assertConflict()->assertDontSee('"giver"', false);
    }

    public function testGroupPageContainsOnlyTheViewersGroupSpecificAssignment(): void
    {
        $this->withoutVite();
        foreach ([$this->admin, $this->member] as $viewer) {
            $response = $this->get("/wichtelgroup/{$this->group->id}?token={$viewer->api_token}");
            $response->assertOk()->assertDontSee('buddy_id')->assertViewHas('isAdmin', $viewer->is($this->admin));
            $this->assertSame(
                $viewer->is($this->admin)
                    ? ['name' => 'Ben', 'wishlist' => null]
                    : ['name' => 'Clara', 'wishlist' => 'Ein Buch'],
                json_decode($response->viewData('buddy'), true, flags: JSON_THROW_ON_ERROR),
            );
        }
    }

    public function testGeneralMemberResponsesOmitAssignments(): void
    {
        $this->actingAs($this->member, 'api');
        $members = "/api/v1/wichtelgroups/{$this->group->id}/wichtelmembers";
        $this->getJson($members)->assertOk()->assertDontSee('buddy_id');
        $this->getJson("{$members}/{$this->admin->id}")->assertOk()->assertDontSee('buddy_id');
        $this->putJson("{$members}/{$this->member->id}", ['wishlist' => 'Tee'])->assertOk()->assertDontSee('buddy_id');
    }
}

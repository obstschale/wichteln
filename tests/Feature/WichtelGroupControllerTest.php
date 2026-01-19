<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WichtelGroupControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    public function testUpdateGroupStatusWithoutNameAndDate(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'name' => 'Test Group',
            'date' => Carbon::now()->subDays(5),
            'status' => 'created',
        ]);

        $group->users()->attach($user, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user, 'api')->putJson("/api/v1/wichtelgroups/{$group->id}", [
            'status' => 'started',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'status' => 'started',
        ]);
    }

    public function testUpdateGroupWithPastDateDoesNotFail(): void
    {
        $user = User::factory()->create();
        $pastDate = Carbon::now()->subDays(10)->format('Y-m-d');
        $group = Group::factory()->create([
            'name' => 'Old Group',
            'date' => $pastDate,
            'status' => 'created',
        ]);

        $group->users()->attach($user, [
            'status' => 'approved',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user, 'api')->putJson("/api/v1/wichtelgroups/{$group->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Name',
        ]);
    }
}

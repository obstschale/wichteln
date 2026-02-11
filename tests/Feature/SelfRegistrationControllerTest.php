<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\Mail\SelfRegistrationConfirmMail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SelfRegistrationControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    public function testShowFormDisplaysRegistrationForm(): void
    {
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'test-join-token-12345678',
        ]);

        $response = $this->get('/join/test-join-token-12345678');

        $response->assertStatus(200);
        $response->assertSee($group->name);
        $response->assertSee('Name');
        $response->assertSee('E-Mail');
        $response->assertSee('Wunschliste');
    }

    public function testShowFormReturns404ForInvalidToken(): void
    {
        $response = $this->get('/join/invalid-token');

        $response->assertStatus(404);
    }

    public function testShowFormShowsClosedPageForStartedGroup(): void
    {
        $group = Group::factory()->create([
            'status' => 'started',
            'join_token' => 'started-group-token-123',
        ]);

        $response = $this->get('/join/started-group-token-123');

        $response->assertStatus(200);
        $response->assertSee('Anmeldung geschlossen');
        $response->assertSee($group->name);
    }

    public function testRegisterCreatesNewUserAndSendsEmail(): void
    {
        Mail::fake();

        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'register-test-token-123',
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/register-test-token-123', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'wishlist' => 'Ein schönes Buch',
            ]);

        $response->assertStatus(200);
        $response->assertSee('Fast geschafft');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'status' => 'invited',
            'wishlist' => 'Ein schönes Buch',
        ]);

        Mail::assertSent(SelfRegistrationConfirmMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function testRegisterCreatesNewUserEvenWithSameEmailInDifferentGroup(): void
    {
        Mail::fake();

        $existingUser = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
        ]);

        $otherGroup = Group::factory()->create(['status' => 'created']);
        $otherGroup->users()->attach($existingUser, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'existing-user-token-123',
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/existing-user-token-123', [
                'name' => 'New Name',
                'email' => 'existing@example.com',
                'wishlist' => 'Etwas Tolles',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('users', 2);

        $newUser = User::where('name', 'New Name')->first();
        $this->assertNotNull($newUser);
        $this->assertNotEquals($existingUser->id, $newUser->id);

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $newUser->id,
            'status' => 'invited',
            'wishlist' => 'Etwas Tolles',
        ]);
    }

    public function testRegisterFailsIfEmailAlreadyInGroup(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'member@example.com',
        ]);

        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'already-member-token-123',
        ]);

        $group->users()->attach($existingUser, [
            'status' => 'approved',
            'is_admin' => false,
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/already-member-token-123', [
                'name' => 'Test',
                'email' => 'member@example.com',
                'wishlist' => '',
            ]);

        $response->assertRedirect('/join/already-member-token-123');
        $response->assertSessionHas('error');
    }

    public function testRegisterFailsForStartedGroup(): void
    {
        $group = Group::factory()->create([
            'status' => 'started',
            'join_token' => 'started-register-token-1',
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/started-register-token-1', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'wishlist' => '',
            ]);

        $response->assertRedirect('/join/started-register-token-1');
        $response->assertSessionHas('error');
    }

    public function testRegisterValidatesRequiredFields(): void
    {
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'validation-test-token-1',
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/validation-test-token-1', [
                'name' => '',
                'email' => '',
            ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function testRegisterValidatesEmailFormat(): void
    {
        $group = Group::factory()->create([
            'status' => 'created',
            'join_token' => 'email-validation-token',
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/join/email-validation-token', [
                'name' => 'Test',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors(['email']);
    }
}

<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'sanctum.stateful' => [
            config('app.frontend_url'),
        ],
    ]);

    $this->withHeader('Origin', config('app.frontend_url'));
});

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

it('authenticates a user using session authentication', function () {

    $user = User::factory()->create([
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson(route('auth.session.login'), [
        'email'    => 'user@example.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonFragment([
            'id'    => $user->id,
            'email' => $user->email,
        ]);

    $this->assertAuthenticatedAs($user, 'web');

});

it('rejects invalid login credentials', function () {

    User::factory()->create([
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson(route('auth.session.login'), [
        'email'    => 'user@example.com',
        'password' => 'incorrect-password',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertGuest('web');

});

it('validates login credentials', function () {

    $response = $this->postJson(route('auth.session.login'), []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);

});

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

it('registers a user and establishes a session', function () {

    Event::fake([Registered::class]);

    $response = $this->postJson(route('auth.session.register'), [
        'name'                  => 'John Doe',
        'email'                 => 'john@example.com',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $user = User::where('email', 'john@example.com')->first();

    expect($user)->not->toBeNull();

    $response->assertCreated()
        ->assertJsonFragment([
            'id'    => $user->id,
            'name'  => 'John Doe',
            'email' => 'john@example.com',
        ]);

    expect(Hash::check('Password123!', $user->password))->toBeTrue();

    $this->assertAuthenticatedAs($user, 'web');

    Event::assertDispatched(Registered::class);

});

it('rejects registration with an existing email address', function () {

    User::factory()->create([
        'email' => 'john@example.com',
    ]);

    $response = $this->postJson(route('auth.session.register'), [
        'name'                  => 'John Doe',
        'email'                 => 'john@example.com',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertDatabaseCount('users', 1);

});

it('validates registration fields', function () {

    $response = $this->postJson(route('auth.session.register'), []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password',
        ]);

});

it('requires password confirmation during registration', function () {

    $response = $this->postJson(route('auth.session.register'), [
        'name'                  => 'John Doe',
        'email'                 => 'john@example.com',
        'password'              => 'Password123!',
        'password_confirmation' => 'DifferentPassword123!',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);

    $this->assertGuest('web');

});

it('prevents authenticated users from registering another account', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'web')
        ->postJson(route('auth.session.register'), [
            'name'                  => 'Jane Doe',
            'email'                 => 'jane@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseMissing('users', [
        'email' => 'jane@example.com',
    ]);

});

/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/

it('returns the authenticated user', function () {

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'web')
        ->getJson(route('auth.session.user'));

    $response
        ->assertOk()
        ->assertJsonFragment([
            'id'    => $user->id,
            'email' => $user->email,
        ]);

});

it('rejects unauthenticated user requests', function () {

    $response = $this->getJson(route('auth.session.user'));

    $response->assertUnauthorized();

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

it('logs out the authenticated user', function () {

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'web')
        ->postJson(route('auth.session.destroy'));

    $response->assertNoContent();

});

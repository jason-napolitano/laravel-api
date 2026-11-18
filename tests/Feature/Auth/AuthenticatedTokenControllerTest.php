<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Guard;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create the default application role.
    $this->defaultRole = config('auth.default_role');

    Role::findOrCreate($this->defaultRole, Guard::getDefaultName(User::class));

    // Create a user for authentication tests.
    $this->user = User::factory()->create([
        'password' => 'password',
    ]);
});

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

describe('POST /auth/login', function () {
    it('authenticates a user and creates an access token', function () {
        $response = $this->postJson(route('auth.login'), [
            'email'       => $this->user->email,
            'password'    => 'password',
            'device_name' => 'Desktop',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
            ])
            ->assertJsonPath('user.id', $this->user->id)
            ->assertJsonPath('user.email', $this->user->email);

        expect($response->json('token'))
            ->toBeString()
            ->and($response->json('token'))
            ->not
            ->toBeEmpty();

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'name'         => 'Desktop',
        ]);
    });

    it('rejects an incorrect password', function () {
        $this->postJson(route('auth.login'), [
            'email'       => $this->user->email,
            'password'    => 'incorrect-password',
            'device_name' => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password')
            ->assertJsonPath('errors.password.0', 'The credentials provided are incorrect.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    });

    it('rejects an email address that does not exist', function () {
        $this->postJson(route('auth.login'), [
            'email'       => 'missing@example.com',
            'password'    => 'password',
            'device_name' => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    });

    it('requires all login fields', function () {
        $this->postJson(route('auth.login'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'email',
                'password',
                'device_name',
            ]);
    });

    it('rejects an invalid email address', function () {
        $this->postJson(route('auth.login'), [
            'email'       => 'not-an-email',
            'password'    => 'password',
            'device_name' => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('rejects a password shorter than six characters', function () {
        $this->postJson(route('auth.login'), [
            'email'       => $this->user->email,
            'password'    => '123',
            'device_name' => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    });
});

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

describe('POST /auth/register', function () {
    it('registers a user and creates an access token', function () {
        $response = $this->postJson(route('auth.register'), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'device_name'           => 'Desktop',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
            ])
            ->assertJsonPath('user.name', 'John Doe')
            ->assertJsonPath('user.email', 'john@example.com');

        $user = User::where('email', 'john@example.com')->firstOrFail();

        expect(Hash::check('password', $user->password))->toBeTrue()
            ->and($user->hasRole($this->defaultRole))->toBeTrue()
            ->and($response->json('token'))->toBeString()
            ->and($response->json('token'))->not->toBeEmpty();

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name'         => 'Desktop',
        ]);
    });

    it('requires all registration fields', function () {
        $this->postJson(route('auth.register'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
                'password_confirmation',
                'device_name',
            ]);
    });

    it('rejects an email address that is already registered', function () {
        $this->postJson(route('auth.register'), [
            'name'                  => 'Another User',
            'email'                 => $this->user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
            'device_name'           => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('requires password confirmation to match', function () {
        $this->postJson(route('auth.register'), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'different-password',
            'device_name'           => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    });

    it('rejects a password shorter than six characters', function () {
        $this->postJson(route('auth.register'), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => '123',
            'password_confirmation' => '123',
            'device_name'           => 'Desktop',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    });
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

describe('POST /auth/logout', function () {
    it('revokes the current access token', function () {
        $currentToken = $this->user->createToken('Desktop');
        $otherToken = $this->user->createToken('Mobile');

        $this->withToken($currentToken->plainTextToken)
            ->postJson(route('auth.logout'))
            ->assertNoContent();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $currentToken->accessToken->id,
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $otherToken->accessToken->id,
        ]);
    });

    it('requires authentication', function () {
        $this->postJson(route('auth.logout'))
            ->assertUnauthorized();
    });
});

/*
|--------------------------------------------------------------------------
| Logout All
|--------------------------------------------------------------------------
*/

describe('POST /auth/logout-all', function () {
    it('revokes all access tokens belonging to the user', function () {
        $currentToken = $this->user->createToken('Desktop');
        $this->user->createToken('Mobile');
        $this->user->createToken('Tablet');

        $this->withToken($currentToken->plainTextToken)
            ->postJson(route('auth.logout-all'))
            ->assertNoContent();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    });

    it('requires authentication', function () {
        $this->postJson(route('auth.logout-all'))
            ->assertUnauthorized();
    });

    it('does not revoke tokens belonging to other users', function () {
        $otherUser = User::factory()->create();

        $currentToken = $this->user->createToken('Desktop');
        $otherUserToken = $otherUser->createToken('Mobile');

        $this->withToken($currentToken->plainTextToken)
            ->postJson(route('auth.logout-all'))
            ->assertNoContent();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $currentToken->accessToken->id,
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $otherUserToken->accessToken->id,
        ]);
    });
});

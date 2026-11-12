<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Forgot Password
|--------------------------------------------------------------------------
*/

it('sends a password reset notification to an existing user', function () {
    Notification::fake();

    $user = User::factory()->create();
    $response = $this->postJson(route('auth.forgot_password'), [
        'email' => $user->email,
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'If an account exists with that email address, a password reset link has been sent.',
        ]);

    Notification::assertSentTo($user, function (ResetPasswordNotification $notification) {
        return !empty($notification->token);
    });
});

it('does not reveal whether an email address exists', function () {
    Notification::fake();

    $response = $this->postJson(route('auth.forgot_password'), [
        'email' => 'nonexistent@example.com',
    ]);
    $response
        ->assertOk()
        ->assertJson([
            'message' => 'If an account exists with that email address, a password reset link has been sent.',
        ]);

    Notification::assertNothingSent();
});

it('validates the email address when requesting a reset', function () {
    $this->postJson(route('auth.forgot_password'), [
        'email' => 'invalid-email',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/

it('successfully resets a user password', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $newPassword = 'NewSecurePassword123!';

    $response = $this->postJson(route('auth.reset_password'), [
        'email'                 => $user->email,
        'token'                 => $token,
        'password'              => $newPassword,
        'password_confirmation' => $newPassword,
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'Your password has been successfully reset.',
        ]);

    expect(Hash::check($newPassword, $user->fresh()->password))->toBeTrue();
});

it('rejects an invalid password reset token', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('auth.reset_password'), [
        'email'                 => $user->email,
        'token'                 => 'invalid-token',
        'password'              => 'NewSecurePassword123!',
        'password_confirmation' => 'NewSecurePassword123!',
    ]);
    $response->assertUnprocessable();

    expect(Hash::check('NewSecurePassword123!', $user->fresh()->password))->toBeFalse();
});

it('does not allow a password reset token to be reused', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $payload = [
        'email'                 => $user->email,
        'token'                 => $token,
        'password'              => 'NewSecurePassword123!',
        'password_confirmation' => 'NewSecurePassword123!',
    ];

    $this->postJson(route('auth.reset_password'), $payload)->assertOk();

    $this->postJson(route('auth.reset_password'), [
        ...$payload,
        'password'              => 'AnotherSecurePassword123!',
        'password_confirmation' => 'AnotherSecurePassword123!',
    ])->assertUnprocessable();
});

it('validates password confirmation when resetting a password', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->postJson(route('auth.reset_password'), [
        'email'                 => $user->email,
        'token'                 => $token,
        'password'              => 'NewSecurePassword123!',
        'password_confirmation' => 'DifferentPassword123!',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('requires all password reset fields', function () {
    $this->postJson(route('auth.reset_password'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'token',
            'password',
        ]);
});

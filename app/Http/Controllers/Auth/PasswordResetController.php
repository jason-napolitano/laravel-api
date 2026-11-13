<?php

namespace App\Http\Controllers\Auth {

    use Illuminate\Auth\Events\PasswordReset;
    use Illuminate\Support\Facades\Password;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use App\Models\User;

    class PasswordResetController extends Controller
    {
        /**
         * Send a password reset link.
         *
         * @param Request $request
         *
         * @return JsonResponse
         */
        public function sendResetLink(Request $request): JsonResponse
        {
            abort_if(!$this->settings->enable_password_reset, 404, 'Not Found');

            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Avoid exposing whether an email exists.
            if ($status === Password::RESET_THROTTLED) {
                return response()->json([
                    'message' => 'Please wait before requesting another password reset.',
                ], 429);
            }

            return response()->json([
                'message' => 'If an account exists with that email address, a password reset link will be sent.',
            ]);
        }

        /**
         * Reset the user's password.
         *
         * @param Request $request
         *
         * @return JsonResponse
         */
        public function resetPassword(Request $request): JsonResponse
        {
            $request->validate([
                'token'    => ['required', 'string'],
                'email'    => ['required', 'email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'message' => 'Your password has been successfully reset.',
                ]);
            }

            return response()->json([
                'message' => $status,
            ], 422);
        }
    }
}

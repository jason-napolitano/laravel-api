<?php

namespace App\Http\Controllers\Auth\Session {

    use Illuminate\Validation\ValidationException;
    use App\Http\Requests\Auth as AuthRequests;
    use Illuminate\Auth\Events\Registered;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\Controller;
    use App\Http\Resources\UserResource;
    use App\Models\User;
    use Illuminate\Http;

    class AuthenticationController extends Controller
    {
        /**
         * Authenticate a user
         *
         * @param \App\Http\Requests\Auth\Token\AuthenticationRequest $request
         *
         * @return Http\JsonResponse
         */
        public function authenticate(AuthRequests\Session\AuthenticationRequest $request): Http\Resources\Json\JsonResource
        {
            $remember = $request['remember'] ?? false;

            unset($request['remember']);

            if (!Auth::guard('web')->attempt($request->validated(), $remember)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $request->session()->regenerate();

            return UserResource::make($request->user('web'));
        }

        /**
         * Register a new user
         *
         * @param \App\Http\Requests\Auth\Token\RegisterUserRequest $request
         *
         * @return Http\JsonResponse
         */
        public function register(AuthRequests\Session\RegisterUserRequest $request): Http\Resources\Json\JsonResource
        {

            $user = User::create($request->validated());

            // Dispatch registration event.
            event(new Registered($user));

            // Authenticate the newly registered user.
            Auth::guard('web')->login($user);

            // Prevent session fixation.
            $request->session()->regenerate();

            return UserResource::make($user);
        }

        /**
         * Logout of a single session
         *
         * @param Http\Request $request
         *
         * @return Http\Response
         */
        public function destroy(Http\Request $request): Http\Response
        {
            Auth::guard('web')->logout();

            // Invalidate the current session.
            $request->session()->invalidate();

            // Generate a new CSRF token.
            $request->session()->regenerateToken();

            return response()->noContent();
        }

        /**
         * Return the currently authenticated user.
         */
        public function user(Http\Request $request): Http\Resources\Json\JsonResource
        {
            return UserResource::make($request->user());
        }
    }
}

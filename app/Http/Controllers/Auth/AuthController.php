<?php

namespace App\Http\Controllers\Auth {

    use Illuminate\Validation\ValidationException;
    use App\Http\Requests\Auth as AuthRequests;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Resources\UserResource;
    use Illuminate\Http;
    use App\Models\User;

    class AuthController extends Controller
    {
        /**
         * Authenticate a user
         *
         * @param AuthRequests\AuthenticateUser $request
         *
         * @return Http\JsonResponse
         */
        public function authenticate(AuthRequests\AuthenticateUser $request): Http\JsonResponse
        {
            // locate the user
            $user = User::where('email', $request['email'])->first();

            // perform password validation
            if (!$user || !Hash::check($request['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['The provided password is incorrect.'],
                ]);
            }

            // generate the token
            $token = $user->createToken($request['device_name'])->plainTextToken;

            // return the response
            return response()->json([
                'user'  => UserResource::make($user),
                'token' => $token,
            ]);
        }

        /**
         * Register a new user
         *
         * @param AuthRequests\RegisterUser $request
         *
         * @return Http\JsonResponse
         */
        public function register(AuthRequests\RegisterUser $request): Http\JsonResponse
        {
            // validate the request
            $request->validated();

            // create the user
            $user = User::create($request->only('name', 'email', 'password'));

            // assign the default role
            $user->assignRole(env('APP_DEFAULT_ROLE'));

            // generate the token
            $token = $user->createToken($request['device_name'])->plainTextToken;

            // return the response
            return response()->json([
                'user'  => UserResource::make($user),
                'token' => $token,
            ], 201);
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
            $request->user()->currentAccessToken()->delete();
            return response()->noContent();
        }

        /**
         * Logout of all sessions on all devices
         *
         * @param Http\Request $request
         *
         * @return Http\Response
         */
        public function destroyAll(Http\Request $request): Http\Response
        {
            $request->user()->tokens()->delete();
            return response()->noContent();
        }
    }
}

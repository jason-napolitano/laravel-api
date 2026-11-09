<?php

namespace App\Http\Requests\Auth {

    use App\Http\Requests\BaseRequest;

    class AuthenticateUserRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'email'       => 'required|string|email|max:255|exists:users,email',
                'device_name' => 'required|string|max:255',
                'password'    => 'required|string|min:6',
            ];
        }
    }
}

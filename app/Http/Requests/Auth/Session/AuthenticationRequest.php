<?php

namespace App\Http\Requests\Auth\Session {

    use App\Http\Requests\BaseRequest;

    class AuthenticationRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'email'    => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'string'],
                'remember' => ['sometimes', 'boolean'],
            ];
        }
    }
}

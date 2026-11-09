<?php

namespace App\Http\Requests\Auth {

    use App\Http\Requests\BaseRequest;

    class RegisterUserRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string',
                'device_name'           => 'required|string|max:255',
            ];
        }
    }
}

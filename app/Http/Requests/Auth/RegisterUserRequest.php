<?php

namespace App\Http\Requests\Auth {

    use Illuminate\Validation\Rules\Password;
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
                'name'                  => ['required', 'string', 'max:255'],
                'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password'              => ['required', 'string', 'confirmed', Password::defaults()],
                'password_confirmation' => ['required', 'string'],
                'device_name'           => ['required', 'string', 'max:255'],
            ];
        }
    }
}

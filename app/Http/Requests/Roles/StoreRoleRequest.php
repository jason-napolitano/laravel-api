<?php

namespace App\Http\Requests\Roles {

    use App\Http\Requests\BaseRequest;

    class StoreRoleRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            return [
                'name'       => ['required', 'string', 'unique:roles,name'],
                'guard_name' => ['required', 'string'],
            ];
        }
    }
}

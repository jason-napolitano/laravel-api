<?php

namespace App\Http\Requests\Permissions {

    use App\Http\Requests\BaseRequest;

    class StorePermissionRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            return [
                'name'       => ['required', 'string', 'unique:permissions,name'],
                'guard_name' => ['required', 'string'],
            ];
        }
    }
}

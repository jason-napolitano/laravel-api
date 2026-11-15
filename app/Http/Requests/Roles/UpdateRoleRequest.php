<?php

namespace App\Http\Requests\Roles {

    use App\Http\Requests\BaseRequest;
    use Illuminate\Validation\Rule;

    class UpdateRoleRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            return [
                'name'       => ['sometimes', 'string', Rule::unique('roles')->ignore($this->route('role'))],
                'guard_name' => ['sometimes', 'string'],
            ];
        }
    }
}

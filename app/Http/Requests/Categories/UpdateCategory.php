<?php

namespace App\Http\Requests\Categories {

    use App\Http\Requests\BaseRequest;

    class UpdateCategory extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'        => 'nullable|string|unique:categories',
                'description' => 'nullable|string',
            ];
        }
    }
}

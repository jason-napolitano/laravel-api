<?php

namespace App\Http\Requests\Categories {

    use App\Http\Requests\BaseRequest;

    class StoreCategory extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'        => 'required|string|unique:categories',
                'description' => 'required|string',
            ];
        }
    }
}

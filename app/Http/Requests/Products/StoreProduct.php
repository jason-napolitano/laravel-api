<?php

namespace App\Http\Requests\Products {

    use App\Http\Requests\BaseRequest;

    class StoreProduct extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'        => 'required|string',
                'price'       => 'required|numeric|min:0',
                'category_id' => 'required|string|exists:categories,id',
                'user_id'     => 'required|string|exists:users,id',
            ];
        }
    }
}

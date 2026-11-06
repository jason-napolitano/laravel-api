<?php

namespace App\Http\Requests\Products {

    use App\Http\Requests\BaseRequest;

    class UpdateProduct extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'  => 'nullable|string|unique:products',
                'price' => 'nullable|numeric|min:0',
            ];
        }
    }
}

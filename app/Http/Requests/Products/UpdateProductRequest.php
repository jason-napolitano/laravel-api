<?php

namespace App\Http\Requests\Products {

    use App\Http\Requests\BaseRequest;
    use Illuminate\Validation\Rule;

    class UpdateProductRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name'  => ['sometimes', 'string', Rule::unique('products', 'name')->ignore($this->route('product')),],
                'price' => ['sometimes', 'numeric', 'min:0'],
            ];
        }
    }
}

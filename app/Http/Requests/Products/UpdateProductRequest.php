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
                'name' => ['nullable', 'string', Rule::unique('products', 'name')->ignore($this->route('product')),],
                'price' => 'nullable|numeric|min:0',
            ];
        }
    }
}

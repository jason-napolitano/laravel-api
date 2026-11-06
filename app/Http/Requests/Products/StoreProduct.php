<?php

namespace App\Http\Requests\Products {

    use Illuminate\Foundation\Http\FormRequest;

    class StoreProduct extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool
        {
            return true;
        }

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

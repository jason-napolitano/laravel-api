<?php

namespace App\Http\Requests\Categories {

    use Illuminate\Foundation\Http\FormRequest;

    class UpdateCategory extends FormRequest
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
                'name'        => 'nullable|string|unique:categories',
                'description' => 'nullable|string',
            ];
        }
    }
}

<?php

namespace App\Http\Requests\Categories {

    use App\Http\Requests\BaseRequest;
    use Illuminate\Validation\Rule;

    class UpdateCategoryRequest extends BaseRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            return [
                'name' => ['nullable', 'string', Rule::unique('categories', 'name')->ignore($this->route('category')),],
                'description' => 'nullable|string',
            ];
        }
    }
}

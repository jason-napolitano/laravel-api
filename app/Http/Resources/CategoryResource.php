<?php

namespace App\Http\Resources {

    use Illuminate\Http;

    class CategoryResource extends Http\Resources\Json\JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Http\Request $request): array
        {
            return [
                'id'          => $this->id,
                'name'        => $this->name,
                'description' => $this->description,
                'products'    => ProductResource::collection($this->whenLoaded('products')),
            ];
        }
    }
}

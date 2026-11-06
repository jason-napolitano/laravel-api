<?php

namespace App\Http\Resources {

    use Illuminate\Http\Resources;
    use Illuminate\Http\Request;

    class CategoryResource extends Resources\Json\JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Request $request): array
        {
            return [
                'id'          => $this['id'],
                'name'        => $this['name'],
                'description' => $this['description'],
                'products'    => ProductResource::collection($this->whenLoaded('products')),
            ];
        }
    }
}

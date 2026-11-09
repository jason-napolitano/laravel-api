<?php

namespace App\Http\Resources {

    use Illuminate\Http;

    class ProductResource extends Http\Resources\Json\JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Http\Request $request): array
        {
            return [
                'id'       => $this->id,
                'name'     => $this->name,
                'price'    => $this->price,
                'user'     => UserResource::make($this->whenLoaded('user')),
                'category' => CategoryResource::make($this->whenLoaded('category')),
            ];
        }
    }
}

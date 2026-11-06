<?php

namespace App\Http\Resources {

    use Illuminate\Http\Resources;
    use Illuminate\Http\Request;

    class ProductResource extends Resources\Json\JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Request $request): array
        {
            return [
                'id'       => $this['id'],
                'name'     => $this['name'],
                'price'    => $this['price'],
                'user'     => UserResource::make($this->whenLoaded('user')),
                'category' => CategoryResource::make($this->whenLoaded('category')),
            ];
        }
    }
}

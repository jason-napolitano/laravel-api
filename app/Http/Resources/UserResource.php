<?php

namespace App\Http\Resources {

    use Illuminate\Http;

    class UserResource extends Http\Resources\Json\JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Http\Request $request): array
        {
            return [
                'id'    => $this->id,
                'name'  => $this->name,
                'email' => $this->email,
            ];
        }
    }
}

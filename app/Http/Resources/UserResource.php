<?php

namespace App\Http\Resources {

    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Http\Request;

    class UserResource extends JsonResource
    {
        /**
         * @inheritdoc
         */
        public function toArray(Request $request): array
        {
            return [
                'id'    => $this['id'],
                'name'  => $this['name'],
                'email' => $this['email'],
            ];
        }
    }
}

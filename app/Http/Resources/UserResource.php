<?php

namespace App\Http\Resources {

    use Illuminate\Http\Resources;
    use Illuminate\Http\Request;

    class UserResource extends Resources\Json\JsonResource
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

<?php

namespace App\Http\Resources {

    use Illuminate\Http;

    class PermissionResource extends Http\Resources\Json\JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Http\Request $request): array
        {
            return [
                'name'       => $this->name,
                'guard_name' => $this->guard_name
            ];
        }
    }
}

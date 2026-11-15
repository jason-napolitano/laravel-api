<?php

namespace App\Http\Resources {

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources;

    class PermissionResource extends Resources\Json\JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return [
                'name'       => $this->name,
                'guard_name' => $this->guard_name
            ];
        }
    }
}

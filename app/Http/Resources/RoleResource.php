<?php

namespace App\Http\Resources {

    use Illuminate\Http\Resources;
    use Illuminate\Http\Request;

    class RoleResource extends Resources\Json\JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return [
                'name'        => $this->name,
                'guard_name'  => $this->guard_name,
            ];
        }
    }
}

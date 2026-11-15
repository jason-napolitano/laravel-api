<?php

namespace App\Http\Controllers\API {

    use App\Http\Requests\Roles as RoleRequests;
    use App\Http\Controllers\Controller;
    use App\Http\Resources\RoleResource;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models\Role;

    class RoleController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index(): Json\ResourceCollection
        {
            return Role::paginate(5)->toResourceCollection();
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(RoleRequests\StoreRoleRequest $request): Json\JsonResource
        {
            return Role::create($request->validated())->toResource();
        }

        /**
         * Display the specified resource.
         */
        public function show(Role $role): Json\JsonResource
        {
            return RoleResource::make($role);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(RoleRequests\UpdateRoleRequest $request, Role $role): Json\JsonResource
        {
            $role->update($request->validated());
            return $role->toResource();
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Role $role): Response
        {
            $role->delete();
            return response()->noContent();
        }
    }
}

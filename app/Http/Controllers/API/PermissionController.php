<?php

namespace App\Http\Controllers\API {

    use App\Http\Requests\Permissions as PermissionRequests;
    use App\Http\Resources\PermissionResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models\Permission;

    class PermissionController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index(): Json\ResourceCollection
        {
            return Permission::paginate(5)->toResourceCollection();
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(PermissionRequests\StorePermissionRequest $request): Json\JsonResource
        {
            return Permission::create($request->validated())->toResource();
        }

        /**
         * Display the specified resource.
         */
        public function show(Permission $permission): Json\JsonResource
        {
            return PermissionResource::make($permission);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(PermissionRequests\UpdatePermissionRequest $request, Permission $permission): Json\JsonResource
        {
            $permission->update($request->validated());
            return $permission->toResource();
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Permission $permission): Response
        {
            $permission->delete();
            return response()->noContent();
        }
    }
}

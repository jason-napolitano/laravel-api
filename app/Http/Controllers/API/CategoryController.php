<?php

namespace App\Http\Controllers\API {

    use Illuminate\Routing\Attributes\Controllers\Authorize;
    use App\Http\Requests\Categories as CategoryRequests;
    use App\Http\Resources\CategoryResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models;

    class CategoryController extends Controller
    {
        #[Authorize('viewAny', Models\Category::class)]
        public function index(): Json\ResourceCollection
        {
            return Models\Category::paginate(5)->toResourceCollection();
        }

        #[Authorize('view', 'category')]
        public function show(Models\Category $category): Json\JsonResource
        {
            return $category->load(['products' => fn ($query) => $query->getLatest()])->toResource();
        }

        #[Authorize('create', Models\Category::class)]
        public function store(CategoryRequests\StoreCategoryRequest $request): CategoryResource
        {
            return Models\Category::create($request->validated())->toResource();
        }

        #[Authorize('update', 'category')]
        public function update(CategoryRequests\UpdateCategoryRequest $request, Models\Category $category): Json\JsonResource
        {
            $category->update($request->validated());
            return $category->toResource();
        }

        #[Authorize('delete', 'category')]
        public function destroy(Models\Category $category): Response
        {
            $category->delete();
            return response()->noContent();
        }
    }
}

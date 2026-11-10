<?php

namespace App\Http\Controllers\API {

    use App\Http\Requests\Categories as CategoryRequests;
    use App\Http\Attributes\AuthorizeResource;
    use App\Http\Resources\CategoryResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models;

    #[AuthorizeResource(Models\Category::class, 'category')]
    class CategoryController extends Controller
    {
        public function index(): Json\ResourceCollection
        {
            return Models\Category::paginate(5)->toResourceCollection();
        }

        public function show(Models\Category $category): Json\JsonResource
        {
            return $category->load(['products' => fn ($query) => $query->getLatest()])->toResource();
        }

        public function store(CategoryRequests\StoreCategoryRequest $request): CategoryResource
        {
            return Models\Category::create($request->validated())->toResource();
        }

        public function update(CategoryRequests\UpdateCategoryRequest $request, Models\Category $category): Json\JsonResource
        {
            $category->update($request->validated());
            return $category->toResource();
        }

        public function destroy(Models\Category $category): Response
        {
            $category->delete();
            return response()->noContent();
        }
    }
}

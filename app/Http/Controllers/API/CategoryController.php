<?php

namespace App\Http\Controllers\API {

    use App\Models\Product;
    use Illuminate\Routing\Attributes\Controllers\Authorize;
    use App\Http\Requests\Categories\UpdateCategoryRequest;
    use App\Http\Requests\Categories\StoreCategoryRequest;
    use App\Http\Resources\CategoryResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models\Category;

    class CategoryController extends Controller
    {
        #[Authorize('viewAny', Category::class)]
        public function index(): Json\ResourceCollection
        {
            return Category::paginate(5)->toResourceCollection();
        }

        #[Authorize('view', 'category')]
        public function show(Category $category): Json\JsonResource
        {
            return $category->load(['products' => fn (Product $query) => $query->getLatest()])->toResource();
        }

        #[Authorize('create', Category::class)]
        public function store(StoreCategoryRequest $request): CategoryResource
        {
            return Category::create($request->validated())->toResource();
        }

        #[Authorize('update', 'category')]
        public function update(UpdateCategoryRequest $request, Category $category): Json\JsonResource
        {
            $category->update($request->validated());
            return $category->toResource();
        }

        #[Authorize('delete', 'category')]
        public function destroy(Category $category): Response
        {
            $category->delete();
            return response()->noContent();
        }
    }
}

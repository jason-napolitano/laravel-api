<?php

namespace App\Http\Controllers\API {

    use App\Http\Requests\Categories as CategoryRequests;
    use App\Http\Attributes\AuthorizeMany;
    use App\Http\Resources\CategoryResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models\Category;

    #[AuthorizeMany(model: Category::class, parameter: 'category')]
    class CategoryController extends Controller
    {
        public function index(): Json\ResourceCollection
        {
            return Category::paginate(5)->toResourceCollection();
        }

        public function show(Category $category): Json\JsonResource
        {
            return $category->load(['products' => fn ($query) => $query->getLatest()])->toResource();
        }

        public function store(CategoryRequests\StoreCategoryRequest $request): CategoryResource
        {
            return Category::create($request->validated())->toResource();
        }

        public function update(CategoryRequests\UpdateCategoryRequest $request, Category $category): Json\JsonResource
        {
            $category->update($request->validated());
            return $category->toResource();
        }

        public function destroy(Category $category): Response
        {
            $category->delete();
            return response()->noContent();
        }
    }
}

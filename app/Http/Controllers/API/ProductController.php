<?php

namespace App\Http\Controllers\API {

    use Illuminate\Routing\Attributes\Controllers\Authorize;
    use App\Http\Requests\Products as ProductRequests;
    use App\Http\Filters\Products\GetCategoryName;
    use App\Http\Resources\ProductResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use Spatie\QueryBuilder;
    use App\Models\Product;

    class ProductController extends Controller
    {
        #[Authorize('viewAny', Product::class)]
        public function index(): Json\ResourceCollection
        {
            $products = QueryBuilder\QueryBuilder::for(Product::class)
                ->allowedFilters(
                    QueryBuilder\AllowedFilter::custom('category', new GetCategoryName()),
                )
                ->paginate(5);

            return $products->toResourceCollection();
        }

        #[Authorize('view', 'product')]
        public function show(Product $product): Json\JsonResource
        {
            return $product->load(['user', 'category'])->toResource();
        }

        #[Authorize('create', Product::class)]
        public function store(ProductRequests\StoreProductRequest $request): ProductResource
        {
            return Product::create($request->validated())->toResource();
        }

        #[Authorize('update', 'product')]
        public function update(ProductRequests\UpdateProductRequest $request, Product $product): Json\JsonResource
        {
            $product->update($request->validated());
            return $product->toResource();
        }

        #[Authorize('delete', 'product')]
        public function destroy(Product $product): Response
        {
            $product->delete();
            return response()->noContent();
        }
    }
}

<?php

namespace App\Http\Controllers\API {

    use Illuminate\Routing\Attributes\Controllers\Authorize;
    use App\Http\Requests\Products\UpdateProductRequest;
    use App\Http\Requests\Products\StoreProductRequest;
    use App\Http\Filters\Products\GetCategoryName;
    use App\Http\Resources\ProductResource;
    use Spatie\QueryBuilder\AllowedFilter;
    use Spatie\QueryBuilder\QueryBuilder;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use App\Models\Product;

    class ProductController extends Controller
    {
        #[Authorize('viewAny', Product::class)]
        public function index(): Json\ResourceCollection
        {
            $products = QueryBuilder::for(Product::class)
                ->allowedFilters(
                    AllowedFilter::custom('category', new GetCategoryName()),
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
        public function store(StoreProductRequest $request): ProductResource
        {
            return Product::create($request->validated())->toResource();
        }

        #[Authorize('update', 'product')]
        public function update(UpdateProductRequest $request, Product $product): Json\JsonResource
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

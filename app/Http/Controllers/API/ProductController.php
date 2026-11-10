<?php

namespace App\Http\Controllers\API {

    use App\Http\Requests\Products as ProductRequests;
    use App\Http\Filters\Products\GetCategoryName;
    use App\Http\Attributes\AuthorizeResource;
    use App\Http\Resources\ProductResource;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json;
    use Illuminate\Http\Response;
    use Spatie\QueryBuilder;
    use App\Models\Product;

    #[AuthorizeResource(model: Product::class, parameter: 'product')]
    class ProductController extends Controller
    {
        public function index(): Json\ResourceCollection
        {
            $products = QueryBuilder\QueryBuilder::for(Product::class)
                ->allowedFilters(
                    QueryBuilder\AllowedFilter::custom('category', new GetCategoryName()),
                )
                ->paginate(5);

            return $products->toResourceCollection();
        }

        public function show(Product $product): Json\JsonResource
        {
            return $product->load(['user', 'category'])->toResource();
        }

        public function store(ProductRequests\StoreProductRequest $request): ProductResource
        {
            return Product::create($request->validated())->toResource();
        }

        public function update(ProductRequests\UpdateProductRequest $request, Product $product): Json\JsonResource
        {
            $product->update($request->validated());
            return $product->toResource();
        }

        public function destroy(Product $product): Response
        {
            $product->delete();
            return response()->noContent();
        }
    }
}

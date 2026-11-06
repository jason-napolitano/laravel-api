<?php

namespace App\Http\Filters\Products {

    use Illuminate\Database\Eloquent\Builder;
    use Spatie\QueryBuilder\Filters\Filter;

    class GetCategoryName implements Filter
    {
        public function __invoke(Builder $query, $value, string $property): void
        {
            $query->whereHas('category', function (Builder $category) use ($value) {
                $category->where('name', $value);
            });
        }
    }
}

<?php

namespace App\Models {

    use App\Observers\CategoryObserver;
    use Illuminate\Database\Eloquent;

    #[Eloquent\Attributes\ObservedBy(CategoryObserver::class)]
    #[Eloquent\Attributes\Fillable(['name', 'description'])]
    class Category extends BaseModel
    {
        // ------------------------------------------------
        // relations

        public function products(): Eloquent\Relations\HasMany
        {
            return $this->hasMany(Product::class, 'category_id', 'id');
        }
    }
}

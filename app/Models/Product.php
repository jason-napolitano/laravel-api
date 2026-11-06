<?php

namespace App\Models {

    use Illuminate\Database\Eloquent;

    #[Eloquent\Attributes\Fillable('name', 'price', 'category_id', 'user_id')]
    class Product extends BaseModel
    {
        // ------------------------------------------------
        // relations

        public function user(): Eloquent\Relations\BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function category(): Eloquent\Relations\BelongsTo
        {
            return $this->belongsTo(Category::class);
        }

        // ------------------------------------------------
        // scopes

        public function scopeGetLatest($builder, $count = 5, $by = 'id', $order = 'asc')
        {
            return $builder->orderBy($by, $order)->limit($count)->get();
        }
    }
}

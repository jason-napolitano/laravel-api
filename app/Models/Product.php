<?php

namespace App\Models {

    use Illuminate\Database\Eloquent;

    /**
     * @method Eloquent\Builder getLatest(int $count = 5, string $by = 'id', string $order = 'asc')
     */
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

        public function scopeGetLatest($builder, $count = 5, $by = 'created_at'): Eloquent\Builder
        {
            return $builder->orderBy($by, 'desc')->limit($count);
        }
    }
}

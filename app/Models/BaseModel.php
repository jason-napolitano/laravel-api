<?php

namespace App\Models {

    use Illuminate\Database\Eloquent;

    /**
     * @method static whereBetween(string $column, array $between)
     * @method static paginate(int $count)
     * @method static find(string|int $id)
     * @method static where($x, $y, ?$x)
     * @method static inRandomOrder()
     * @method static create($data)
     * @method static insert($data)
     * @method static first()
     */
    class BaseModel extends Eloquent\Model
    {
        use Eloquent\Factories\HasFactory;
        use Eloquent\Concerns\HasUuids;
        use Eloquent\SoftDeletes;
    }
}

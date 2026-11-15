<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Attributes\Fillable;
    use Spatie\Permission\Models\Role as BaseRole;

    #[Fillable('name', 'guard_name')]
    class Role extends BaseRole
    {
        //
    }
}

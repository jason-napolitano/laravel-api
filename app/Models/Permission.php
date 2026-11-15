<?php

namespace App\Models {

    use Spatie\Permission\Models\Permission as BasePermission;
    use Illuminate\Database\Eloquent\Attributes\Fillable;

    #[Fillable('name', 'guard_name')]
    class Permission extends BasePermission
    {
        //
    }
}

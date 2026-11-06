<?php

use Illuminate\Contracts\Auth\Authenticatable;

// --------------------------------------------------------
// if the function doesn't exist, let's create it!
if (!function_exists('user')) {
    /**
     * Wrapper for auth()->user()
     *
     * @return Authenticatable|null
     */
    function user(): Authenticatable|null
    {
        return auth()->user();
    }
}

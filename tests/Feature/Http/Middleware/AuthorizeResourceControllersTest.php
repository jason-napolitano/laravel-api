<?php

use Illuminate\Routing\Attributes\Controllers\Authorize;
use App\Http\Middleware\AuthorizeResourceControllers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Attributes\AuthorizeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware(['api', AuthorizeResourceControllers::class])
        ->get('/_tests/products', [TestController::class, 'index',]);

    Route::middleware(['api', AuthorizeResourceControllers::class])
        ->put('/_tests/products/{product}', [TestController::class, 'update',]);
});

/*
|--------------------------------------------------------------------------
| Automatic resource authorization
|--------------------------------------------------------------------------
*/

it('automatically authorizes resource controller methods', function () {
    Gate::define('viewAny', fn (User $user, string $model) => false);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/_tests/products')
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Native Authorize attribute precedence
|--------------------------------------------------------------------------
*/

it('allows the native authorize attribute to override automatic authorization', function () {
    // The middleware would deny this ability.
    Gate::define('update', fn (User $user, string $product) => false);

    // The native attribute allows this ability.
    Gate::define('publish', fn (User $user, string $product) => true);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->putJson('/_tests/products/123')
        ->assertNoContent();
});

it('still enforces authorization through the native authorize attribute', function () {
    // The middleware would allow this ability.
    Gate::define('update', fn (User $user, string $product) => true);

    // The native attribute denies this ability.
    Gate::define('publish', fn (User $user, string $product) => false);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->putJson('/_tests/products/123')
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Test controller fixture
|--------------------------------------------------------------------------
*/

#[AuthorizeController(model: Product::class, parameter: 'product')]
class TestController
{
    public function index()
    {
        return response()->noContent();
    }

    #[Authorize('publish', 'product')]
    public function update(string $product)
    {
        return response()->noContent();
    }
}

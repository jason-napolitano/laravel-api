<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Guard;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->grantPermission = function (string $permission): void {
        $guard = Guard::getDefaultName(User::class);

        Permission::findOrCreate($permission, $guard);

        $this->user->givePermissionTo($permission);
    };

    $this->authenticate = function (): void {
        $this->actingAs($this->user);
    };
});

describe('GET /api/categories', function () {

    it('returns a paginated collection of categories', function () {
        ($this->grantPermission)('view_categories');
        ($this->authenticate)();

        Category::factory()->count(7)->create();

        $this->getJson(route('categories.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 7);
    });

    it('returns the requested pagination page', function () {
        ($this->grantPermission)('view_categories');
        ($this->authenticate)();

        Category::factory()->count(7)->create();

        $this->getJson(route('categories.index', ['page' => 2]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.current_page', 2);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $this->getJson(route('categories.index'))->assertForbidden();
    });

});

describe('GET /api/categories/{category}', function () {

    it('returns a category', function () {
        ($this->grantPermission)('view_category');
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->getJson(route('categories.show', $category))->assertOk();
    });

    it('returns the latest five products belonging to a category', function () {
        ($this->grantPermission)('view_category');
        ($this->authenticate)();

        $category = Category::factory()->create();
        $user = User::factory()->create();

        $products = collect();

        foreach (range(1, 7) as $number) {
            $products->push(Product::create([
                'name'        => "Product $number",
                'price'       => 10 * $number,
                'category_id' => $category->id,
                'user_id'     => $user->id,
            ]));
        }

        $this->getJson(route('categories.show', $category))
            ->assertOk()
            ->assertJsonCount(5, 'data.products');
    });

    it('returns an empty products collection when no products exist', function () {
        ($this->grantPermission)('view_category');
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->getJson(route('categories.show', $category))
            ->assertOk()
            ->assertJsonCount(0, 'data.products');
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->getJson(route('categories.show', $category))
            ->assertForbidden();
    });

    it('returns 404 when the category does not exist', function () {
        ($this->grantPermission)('view_category');
        ($this->authenticate)();

        $this->getJson('/api/categories/999999')
            ->assertNotFound();
    });

});

describe('POST /api/categories', function () {

    it('creates a category', function () {
        ($this->grantPermission)('create_category');
        ($this->authenticate)();

        $data = [
            'name'        => 'Electronics',
            'description' => 'Electronic products and accessories.',
        ];

        $this->postJson('/api/categories', $data)
            ->assertCreated()
            ->assertJsonPath('data.name', $data['name'])
            ->assertJsonPath('data.description', $data['description']);

        $this->assertDatabaseHas('categories', [
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => 'electronics',
        ]);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $this->postJson(route('categories.store'), [
            'name'        => 'Electronics',
            'description' => 'A category description.',
        ])
            ->assertForbidden();
    });

});

describe('PUT /api/categories/{category}', function () {

    it('updates a category', function () {
        ($this->grantPermission)('update_category');
        ($this->authenticate)();

        $category = Category::factory()->create();

        $data = [
            'name'        => 'Updated Electronics',
            'description' => 'Updated category description.',
        ];

        $this->putJson(route('categories.update', $category), $data)
            ->assertOk()
            ->assertJsonPath('data.name', $data['name'])
            ->assertJsonPath('data.description', $data['description']);

        $this->assertDatabaseHas('categories', [
            'id'          => $category->id,
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => 'updated-electronics',
        ]);
    });

    it('allows updating a category without changing its name', function () {
        ($this->grantPermission)('update_category');
        ($this->authenticate)();

        $category = Category::factory()->create([
            'name' => 'Electronics',
        ]);

        $this->putJson(route('categories.update', $category), [
            'description' => 'New description.',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Electronics')
            ->assertJsonPath('data.description', 'New description.');
    });

    it('allows updating a category with an empty payload', function () {
        ($this->grantPermission)('update_category');
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->putJson(route('categories.update', $category), [])
            ->assertOk();
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->putJson(route('categories.update', $category), [
            'name' => 'Updated Electronics',
        ])
            ->assertForbidden();
    });

});

describe('DELETE /api/categories/{category}', function () {

    it('deletes a category', function () {
        ($this->grantPermission)('delete_category');
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->deleteJson(route('categories.destroy', $category))
            ->assertNoContent();

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $category = Category::factory()->create();

        $this->deleteJson(route('categories.destroy', $category))
            ->assertForbidden();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    });
});

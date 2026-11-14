<?php

use App\Models;

// ------------------------------------------------
// index

describe('GET /api/products', function () {

    it('returns a paginated collection of products', function () {
        ($this->grantPermission)('view_all_products');
        ($this->authenticate)();

        Models\Product::factory()->count(7)->create();

        $this->getJson(route('products.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 7);
    });

    it('returns the requested pagination page', function () {
        ($this->grantPermission)('view_all_products');
        ($this->authenticate)();

        Models\Product::factory()->count(7)->create();

        $this->getJson(route('products.index', ['page' => 2]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.current_page', 2);
    });

    it('filters products by category name', function () {
        ($this->grantPermission)('view_all_products');
        ($this->authenticate)();

        $electronics = Models\Category::factory()->create([
            'name' => 'Electronics',
        ]);

        $furniture = Models\Category::factory()->create([
            'name' => 'Furniture',
        ]);

        Models\Product::factory()->count(3)->create([
            'category_id' => $electronics->id,
        ]);

        Models\Product::factory()->count(2)->create([
            'category_id' => $furniture->id,
        ]);

        $this->getJson(route('products.index', [
            'filter' => [
                'category' => 'Electronics',
            ],
        ]))
            ->assertOk()
            ->assertJsonCount(3, 'data');
    });

    it('returns an empty collection when no products match the filter', function () {
        ($this->grantPermission)('view_all_products');
        ($this->authenticate)();

        Models\Category::factory()->create([
            'name' => 'Electronics',
        ]);

        $this->getJson(route('products.index', [
            'filter' => [
                'category' => 'Electronics',
            ],
        ]))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $this->getJson(route('products.index'))
            ->assertForbidden();
    });

    it('rejects unauthenticated requests', function () {
        $this->getJson(route('products.index'))
            ->assertUnauthorized();
    });

});

// ------------------------------------------------
// show

describe('GET /api/products/{product}', function () {

    it('returns a product', function () {
        ($this->grantPermission)('view_any_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create();

        $this->getJson(route('products.show', $product))
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonPath('data.price', $product->price);
    });

    it('returns the product with its user and category relationships', function () {
        ($this->grantPermission)('view_any_product');
        ($this->authenticate)();

        $user = Models\User::factory()->create();
        $category = Models\Category::factory()->create();

        $product = Models\Product::factory()->create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
        ]);

        $this->getJson(route('products.show', $product))
            ->assertOk()
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.category.id', $category->id);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $product = Models\Product::factory()->create();

        $this->getJson(route('products.show', $product))
            ->assertForbidden();
    });

    it('rejects unauthenticated requests', function () {
        $product = Models\Product::factory()->create();

        $this->getJson(route('products.show', $product))
            ->assertUnauthorized();
    });

    it('returns 404 when the product does not exist', function () {
        ($this->grantPermission)('view_any_product');
        ($this->authenticate)();

        $this->getJson(route('products.show', 9999))
            ->assertNotFound();
    });

});

// ------------------------------------------------
// store

describe('POST /api/products', function () {

    it('creates a product', function () {
        ($this->grantPermission)('create_product');
        ($this->authenticate)();

        $category = Models\Category::factory()->create();

        $data = [
            'name'        => 'Gaming Laptop',
            'price'       => 1299.99,
            'category_id' => $category->id,
            'user_id'     => $this->user->id,
        ];

        $this->postJson(route('products.store'), $data)
            ->assertCreated()
            ->assertJsonPath('data.name', $data['name'])
            ->assertJsonPath('data.price', $data['price']);

        $this->assertDatabaseHas('products', $data);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $category = Models\Category::factory()->create();

        $this->postJson(route('products.store'), [
            'name'        => 'Gaming Laptop',
            'price'       => 100,
            'category_id' => $category->id,
            'user_id'     => $this->user->id,
        ])
            ->assertForbidden();
    });

    it('rejects unauthenticated requests', function () {
        $category = Models\Category::factory()->create();

        $this->postJson(route('products.store'), [
            'name'        => 'Gaming Laptop',
            'price'       => 100,
            'category_id' => $category->id,
            'user_id'     => Models\User::factory()->create()->id,
        ])
            ->assertUnauthorized();
    });

});

// ------------------------------------------------
// update

describe('PUT /api/products/{product}', function () {

    it('updates an owned product', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $data = [
            'name'  => 'Updated Gaming Laptop',
            'price' => 1499.99,
        ];

        $this->putJson(route('products.update', $product), $data)
            ->assertOk()
            ->assertJsonPath('data.name', $data['name'])
            ->assertJsonPath('data.price', $data['price']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            ...$data,
        ]);
    });

    it('allows updating a product without changing its name', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->putJson(route('products.update', $product), [
            'name'  => $product->name,
            'price' => 200,
        ])
            ->assertOk();
    });

    it('allows updating only the price', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->putJson(route('products.update', $product), [
            'price' => 250,
        ])
            ->assertOk()
            ->assertJsonPath('data.price', 250);
    });

    it('prevents updating another users product', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create();

        expect($product->user_id)->not->toBe($this->user->id);

        $this->putJson(route('products.update', $product), [
            'name' => 'Unauthorized Update',
        ])
            ->assertForbidden();

        $this->assertDatabaseMissing('products', [
            'id'   => $product->id,
            'name' => 'Unauthorized Update',
        ]);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->putJson(route('products.update', $product), [
            'name' => 'Updated Product',
        ])
            ->assertForbidden();
    });

    it('does not allow updating the category or owner', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $originalCategory = $product->category_id;

        $this->putJson(route('products.update', $product), [
            'category_id' => Models\Category::factory()->create()->id,
            'user_id'     => Models\User::factory()->create()->id,
        ])
            ->assertOk();

        $this->assertDatabaseHas('products', [
            'id'          => $product->id,
            'category_id' => $originalCategory,
            'user_id'     => $this->user->id,
        ]);
    });

    it('returns 404 when the product does not exist', function () {
        ($this->grantPermission)('update_own_product');
        ($this->authenticate)();

        $this->putJson(route('products.show', 9999), [
            'name' => 'Updated Product',
        ])
            ->assertNotFound();
    });

});

// ------------------------------------------------
// destroy

describe('DELETE /api/products/{product}', function () {

    it('deletes an owned product', function () {
        ($this->grantPermission)('delete_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson(route('products.destroy', $product))
            ->assertNoContent();

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    });

    it('prevents deleting another users product', function () {
        ($this->grantPermission)('delete_own_product');
        ($this->authenticate)();

        $product = Models\Product::factory()->create();

        expect($product->user_id)->not->toBe($this->user->id);

        $this->deleteJson(route('products.destroy', $product))
            ->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    });

    it('denies users without the required permission', function () {
        ($this->authenticate)();

        $product = Models\Product::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->deleteJson(route('products.destroy', $product))
            ->assertForbidden();
    });

    it('rejects unauthenticated requests', function () {
        $product = Models\Product::factory()->create();

        $this->deleteJson(route('products.destroy', $product))
            ->assertUnauthorized();
    });

    it('returns 404 when the product does not exist', function () {
        ($this->grantPermission)('delete_own_product');
        ($this->authenticate)();

        $this->deleteJson('/api/products/999999')
            ->assertNotFound();
    });

});

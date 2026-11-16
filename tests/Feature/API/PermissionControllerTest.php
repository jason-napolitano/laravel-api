<?php

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Guard;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->guardName = Guard::getDefaultName(User::class);

    $this->authenticate = function (): void {
        $this->actingAs($this->user);
    };

    $this->permissions = [
        [
            'name'       => 'view_articles',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'edit_articles',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'delete_articles',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'create_articles',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'update_articles',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'restore_articles',
            'guard_name' => $this->guardName,
        ]
    ];
});

describe('GET /api/permissions', function () {
    it('returns a paginated collection of permissions', function () {
        ($this->authenticate)();

        Permission::insert($this->permissions);

        $this->getJson(route('permissions.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 6);
    });

    it('returns an empty permission collection when no permissions exist', function () {
        ($this->authenticate)();

        $this->getJson(route('permissions.index'))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    });
});

describe('GET /api/permissions/{permission}', function () {
    it('returns a permission', function () {
        ($this->authenticate)();

        $permission = Permission::create($this->permissions[0]);

        $this->getJson(route('permissions.show', $permission))
            ->assertOk();
    });

    it('returns a 404 when the permission does not exist', function () {
        ($this->authenticate)();

        $this->getJson(route('permissions.show', 9999))
            ->assertNotFound();
    });
});

describe('POST /api/permissions', function () {
    it('creates a permission', function () {
        ($this->authenticate)();

        $this->postJson(route('permissions.store', $this->permissions[0]))
            ->assertCreated();

        $this->assertDatabaseHas('permissions', $this->permissions[0]);
    });

    it('does not create a permission that already exists', function () {
        ($this->authenticate)();

        $this->postJson(route('permissions.store', $this->permissions[0]));

        $this->postJson(route('permissions.store', $this->permissions[0]))
            ->assertUnprocessable();
    });
});

describe('PATCH /api/permissions/{permission}', function () {
    it('updates a permission', function () {
        ($this->authenticate)();

        $permission = Permission::create($this->permissions[0]);

        $data = [
            'name' => 'updated_name',
        ];

        $this->patchJson(route('permissions.update', $permission), $data)
            ->assertOk()
            ->assertJsonPath('data.name', $data['name']);

        $this->assertDatabaseHas('permissions', [
            'name' => $data['name'],
        ]);
    });

    it('returns a 404 when a permission does not exist', function () {
        ($this->authenticate)();

        $this->patchJson(route('permissions.update', 9999))
            ->assertNotFound();
    });
});

describe('DELETE /api/permissions/{permission}', function () {
    it('deletes a permission', function () {
        ($this->authenticate)();

        $permission = Permission::create($this->permissions[0]);

        $this->deleteJson(route('permissions.destroy', $permission))
            ->assertNoContent();

        $this->assertDatabaseEmpty('permissions');
    });

    it('returns a 404 when a permission does not exist', function () {
        ($this->authenticate)();

        $this->deleteJson(route('permissions.destroy', 9999))
            ->assertNotFound();
    });
});

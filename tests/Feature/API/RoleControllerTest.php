<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Guard;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->guardName = Guard::getDefaultName(User::class);

    $this->authenticate = function (): void {
        $this->actingAs($this->user);
    };

    $this->roles = [
        [
            'name'       => 'Manager',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'Moderator',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'Creator',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'Publisher',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'Editor',
            'guard_name' => $this->guardName,
        ],
        [
            'name'       => 'Operator',
            'guard_name' => $this->guardName,
        ]
    ];
});

describe('GET /api/roles', function () {
    it('returns a paginated collection of roles', function () {
        ($this->authenticate)();

        Role::insert($this->roles);

        $this->getJson(route('roles.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 6);
    });

    it('returns the requested pagination page', function () {
        ($this->authenticate)();

        Role::insert($this->roles);

        $this->getJson(route('roles.index', ['page' => 2]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2);
    });

    it('returns an empty role collection when no roles exist', function () {
        ($this->authenticate)();

        $this->getJson(route('roles.index'))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    });
});

describe('GET /api/roles/{role}', function () {
    it('returns a role', function () {
        ($this->authenticate)();

        $role = Role::create($this->roles[0]);

        $this->getJson(route('roles.show', $role))
            ->assertOk();
    });

    it('returns a 404 when the role does not exist', function () {
        ($this->authenticate)();

        $this->getJson(route('roles.show', 9999))
            ->assertNotFound();
    });
});

describe('POST /api/roles', function () {
    it('creates a role', function () {
        ($this->authenticate)();

        $this->postJson(route('roles.store', $this->roles[0]))
            ->assertCreated()
            ->assertJsonPath('data.name', $this->roles[0]['name'])
            ->assertJsonPath('data.guard_name', $this->roles[0]['guard_name']);

        $this->assertDatabaseHas('roles', $this->roles[0]);
    });

    it('does not create a role that already exists', function () {
        ($this->authenticate)();

        $this->postJson(route('roles.store', $this->roles[0]));

        $this->postJson(route('roles.store', $this->roles[0]))
            ->assertUnprocessable();
    });
});

describe('PATCH /api/roles/{role}', function () {
    it('updates a role', function () {
        ($this->authenticate)();

        $role = Role::create($this->roles[0]);

        $data = [
            'name' => 'Updated Manager',
        ];

        $this->patchJson(route('roles.update', $role), $data)
            ->assertOk()
            ->assertJsonPath('data.name', $data['name']);

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    });

    it('returns a 404 when the role does not exist', function () {
        ($this->authenticate)();

        $this->patchJson(route('roles.destroy', 9999))
            ->assertNotFound();
    });
});

describe('DELETE /api/roles/{role}', function () {
    it('deletes a role', function () {
        ($this->authenticate)();

        $role = Role::create($this->roles[0]);

        $this->deleteJson(route('roles.destroy', $role))
            ->assertNoContent();

        $this->assertDatabaseEmpty('roles');
    });

    it('returns a 404 when the role does not exist', function () {
        ($this->authenticate)();

        $this->deleteJson(route('roles.destroy', 9999))
            ->assertNotFound();
    });
});

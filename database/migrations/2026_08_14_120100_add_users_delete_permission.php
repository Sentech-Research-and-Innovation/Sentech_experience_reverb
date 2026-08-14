<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * The "delete user" route was previously gated by the "users-create" permission
     * (routes/admin/roles.php), which meant anyone who could create users could also
     * delete them. This introduces a proper "users-delete" permission and grants it
     * to every role that currently has "users-create", so nobody loses access to a
     * feature they already use - going forward, the two can be assigned separately.
     */
    public function up(): void
    {
        $permission = Permission::firstOrCreate([
            'name' => 'users-delete',
            'guard_name' => 'web',
        ]);

        $roleIds = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'users-create')->where('guard_name', 'web');
        })->pluck('id');

        foreach (Role::whereIn('id', $roleIds)->get() as $role) {
            $role->givePermissionTo($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $permission = Permission::where(['name' => 'users-delete', 'guard_name' => 'web'])->first();
        $permission?->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};

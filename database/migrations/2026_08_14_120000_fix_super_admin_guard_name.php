<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Repairs "Super Admin" roles created with guard_name set to the company's
     * name instead of "web" (see RegisteredUserController / OrganizationsController).
     * A mismatched guard_name makes Spatie's hasRole()/hasPermissionTo() checks
     * silently fail for that company's admin, since the "web" guard is what
     * every permission/route check actually uses.
     */
    public function up(): void
    {
        DB::table('roles')
            ->where('name', 'Super Admin')
            ->where('guard_name', '!=', 'web')
            ->update(['guard_name' => 'web']);
    }

    public function down(): void
    {
        // Not reversible: the original (broken) guard_name values aren't recoverable.
    }
};

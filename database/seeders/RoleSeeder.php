<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        $allForGuard = Permission::query()->where('guard_name', $guard)->get();

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => $guard,
        ]);
        $superAdmin->syncPermissions($allForGuard);

        $operator = Role::firstOrCreate([
            'name' => 'operator',
            'guard_name' => $guard,
        ]);
        $operator->syncPermissions(
            $allForGuard->filter(fn (Permission $p) => ! str_ends_with($p->name, '.delete'))
        );
    }
}

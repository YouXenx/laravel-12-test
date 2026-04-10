<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'api'; // 🔥 biar konsisten

        $permissions = [
            'dashboard-menu',
            'family-member-menu',
            'family-member-list',
            'family-member-create',
            'family-member-edit',
            'family-member-delete',

            'social-assistance-menu',
            'social-assistance-list',

            'social-assistance-recipient-menu',
            'social-assistance-recipient-list',
            'social-assistance-recipient-create',
            'social-assistance-recipient-edit',
            'social-assistance-recipient-delete',

            'event-menu',
            'event-list',

            'event-participant-menu',
            'event-participant-list',
            'event-participant-create',
            'event-participant-edit',
            'event-participant-delete',

            'development-menu',
            'development-list',

            'development-applicant-menu',
            'development-applicant-list',
            'development-applicant-create',
            'development-applicant-edit',
            'development-applicant-delete',

            'profile-menu',
        ];

        // 🔥 create permissions (ALL sanctum)
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => $guard,
            ]);
        }

        // 🔥 create role admin
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard,
        ]);

        // 🔥 sync permission (lebih aman daripada givePermissionTo all)
        $role->syncPermissions(Permission::where('guard_name', $guard)->get());
    }
}
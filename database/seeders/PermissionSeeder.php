<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $permissions = [
        'dashboard' => [
            'menu',
        ],
        'head-of-family' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'family-member' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'social-assistance' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'social-assistance-recipient' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'event' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'event-participant' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'development-applicant' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
        'profile' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        foreach ($this->permissions as $group => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$group}.{$action}",
                    'guard_name' => $guard,
                ]);
            }
        }
    }
}

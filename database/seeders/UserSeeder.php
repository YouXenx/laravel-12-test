<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = UserModel::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('admin');

        UserModel::factory()->count(15)->create();
    }
}
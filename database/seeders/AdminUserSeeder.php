<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create the admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create the admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'username' => 'admin@webifly.com',
            'password' => Hash::make('12345678'),
        ]);

        // Assign the admin role to the admin user
        $adminUser->roles()->attach($adminRole);
    }
}

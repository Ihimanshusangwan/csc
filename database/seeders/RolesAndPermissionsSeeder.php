<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $staffManagerRole = Role::create(['name' => 'staff_manager']);

        $manageStaffs = Permission::create(['name' => 'manage staffs']);

        $adminRole->permissions()->attach($manageStaffs);
        $staffManagerRole->permissions()->attach($manageStaffs);
    }
}
<?php 
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Webifly Solutions',
            'email' => 'admin@webifly.com',
            'password' => Hash::make('123')
        ]);

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MigrationController extends Controller
{
    public function migrateAgents()
    {
        // Step 1: Ensure the 'agent' role exists
        $agentRoleId = DB::table('roles')->where('name', 'agent')->value('id');
        if (!$agentRoleId) {
            $agentRoleId = DB::table('roles')->insertGetId([
                'name' => 'agent',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 2: Add the user_id column as nullable if it doesn't exist
        if (!Schema::hasColumn('agents', 'user_id')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
            });
        }

        // Fetch all agents
        $agents = DB::table('agents')->get();

        foreach ($agents as $agent) {
            // Check if user already exists
            $existingUser = DB::table('users')->where('username', $agent->username)->first();
            if ($existingUser) {
                // Update agents table with existing user_id
                DB::table('agents')->where('id', $agent->id)->update(['user_id' => $existingUser->id]);
                DB::table('user_roles')->insert([
                    'user_id' => $existingUser->id,
                    'role_id' => $agentRoleId,
                ]);
            } else {
                // Insert into users table
                $userId = DB::table('users')->insertGetId([
                    'name' => $agent->full_name,
                    'username' => $agent->username,
                    'password' => Hash::make($agent->password),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update agents table with user_id
                DB::table('agents')->where('id', $agent->id)->update(['user_id' => $userId]);

                // Insert into user_roles table
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $agentRoleId,
                ]);
            }
        }

        // Step 3: Make the user_id column not nullable if it is nullable
        if (Schema::hasColumn('agents', 'user_id') && Schema::getColumnType('agents', 'user_id') === 'integer') {
            Schema::table('agents', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        return response()->json(['message' => 'Migration completed successfully.']);
    }

    public function migrateStaff()
    {
        // Step 1: Ensure the 'staff' role exists
        $staffRoleId = DB::table('roles')->where('name', 'staff')->value('id');
        if (!$staffRoleId) {
            $staffRoleId = DB::table('roles')->insertGetId([
                'name' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 2: Add the user_id column as nullable if it doesn't exist
        if (!Schema::hasColumn('staff', 'user_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
            });
        }

        // Fetch all staff
        $staffs = DB::table('staff')->get();

        foreach ($staffs as $staff) {
            // Check if user already exists
            $existingUser = DB::table('users')->where('username', $staff->username)->first();
            if ($existingUser) {
                // Update staffs table with existing user_id
                DB::table('staff')->where('id', $staff->id)->update(['user_id' => $existingUser->id]);
                DB::table('user_roles')->insert([
                    'user_id' => $existingUser->id,
                    'role_id' => $staffRoleId,
                ]);
            } else {
                // Insert into users table
                $userId = DB::table('users')->insertGetId([
                    'name' => $staff->name,
                    'username' => $staff->username,
                    'password' => Hash::make($staff->password),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update staffs table with user_id
                DB::table('staff')->where('id', $staff->id)->update(['user_id' => $userId]);

                // Insert into user_roles table
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $staffRoleId,
                ]);
            }
        }

        // Step 3: Make the user_id column not nullable if it is nullable
        if (Schema::hasColumn('staff', 'user_id') && Schema::getColumnType('staff', 'user_id') === 'integer') {
            Schema::table('staff', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        return response()->json(['message' => 'Migration completed successfully.']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['name' => 'Create Asset', 'guard_name' => 'web'],
            ['name' => 'Create Asset Maintenance', 'guard_name' => 'web'],
            ['name' => 'Create Borrower', 'guard_name' => 'web'],
            ['name' => 'Create Category', 'guard_name' => 'web'],
            ['name' => 'Create Penalty', 'guard_name' => 'web'],
            ['name' => 'Create User', 'guard_name' => 'web'],
            ['name' => 'Delete Asset', 'guard_name' => 'web'],
            ['name' => 'Delete Asset Maintenance', 'guard_name' => 'web'],
            ['name' => 'Delete Borrower', 'guard_name' => 'web'],
            ['name' => 'Delete Category', 'guard_name' => 'web'],
            ['name' => 'Delete Penalty', 'guard_name' => 'web'],
            ['name' => 'Delete User', 'guard_name' => 'web'],
            ['name' => 'Read Asset', 'guard_name' => 'web'],
            ['name' => 'Read Asset Maintenance', 'guard_name' => 'web'],
            ['name' => 'Read Borrower', 'guard_name' => 'web'],
            ['name' => 'Read Category', 'guard_name' => 'web'],
            ['name' => 'Read Penalty', 'guard_name' => 'web'],
            ['name' => 'Read User', 'guard_name' => 'web'],
            ['name' => 'Update Asset', 'guard_name' => 'web'],
            ['name' => 'Update Asset Maintenance', 'guard_name' => 'web'],
            ['name' => 'Update Borrower', 'guard_name' => 'web'],
            ['name' => 'Update Category', 'guard_name' => 'web'],
            ['name' => 'Update Penalty', 'guard_name' => 'web'],
            ['name' => 'Update User', 'guard_name' => 'web'],
        ]);
    }
}

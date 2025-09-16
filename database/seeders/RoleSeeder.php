<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Role::insert([
            ['name' => 'head',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'agent', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

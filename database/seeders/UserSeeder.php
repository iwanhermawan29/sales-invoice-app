<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil role berdasarkan nama
        $salesRole   = Role::where('name', 'head')->first();
        $adminRole   = Role::where('name', 'admin')->first();
        $financeRole = Role::where('name', 'agent')->first();

        // Buat user untuk role Sales
        User::create([
            'name'              => 'head',
            'email'             => 'head@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role_id'           => $salesRole->id,
            'remember_token'    => Str::random(10),
        ]);

        // Buat user untuk role Admin
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role_id'           => $adminRole->id,
            'remember_token'    => Str::random(10),
        ]);

        // Buat user untuk role Keuangan
        User::create([
            'name'              => 'agent',
            'email'             => 'agent@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role_id'           => $financeRole->id,
            'remember_token'    => Str::random(10),
        ]);
    }
}

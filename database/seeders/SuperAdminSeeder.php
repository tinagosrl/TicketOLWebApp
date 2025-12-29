<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@ticketol.eu',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'tenant_id' => null, // SuperAdmin belongs to no tenant
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'super admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password')
        ]);

        Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super admin',
        ]);

        $superAdmin->assignRole('super-admin');
    }
}

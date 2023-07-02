<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Menambahkan data pengguna
        User::create([
            'name' => 'Admin',
            'email' => 'admin@yopmail.com',
            'role' => 1,
            'password' => Hash::make('admin'),
        ]);
    }
}

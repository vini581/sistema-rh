<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Gestor RH',
            'email'    => 'admin@sistema.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }
}
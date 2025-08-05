<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Gustavo',
            'email' => 'tavogustavo496@gmail.com',
            'role' => 'admin', // Asignamos el rol de administrador
            'password' => Hash::make('F14sh93x@'), // ¡Cambia esto por una contraseña segura!
        ]);
    }
}


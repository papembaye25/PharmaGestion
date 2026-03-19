<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crée le compte admin si il n'existe pas déjà
        // updateOrCreate évite les doublons si on relance le seeder
        User::updateOrCreate(
            ['email' => 'admin@pharma.com'],   // Condition de recherche
            [
                'name'     => 'Pharmacien Admin',
                'email'    => 'admin@pharma.com',
                'password' => Hash::make('admin123'),  // Hash sécurisé du mot de passe
                'role'     => 'admin',
            ]
        );
    }
}
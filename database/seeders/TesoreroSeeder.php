<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class TesoreroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Tesorero
        $rolTesorero = RolesModel::where('nombre', 'Tesorero')->first();

        if (!$rolTesorero) {
            $this->command->error('El rol de Tesorero no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario tesorero
        User::updateOrCreate(
            ['email' => 'tesorero@colegio.edu.co'],
            [
                'name' => 'Tesorero',
                'email' => 'tesorero@colegio.edu.co',
                'password' => Hash::make('tesorero123'), // Cambiar por una contraseña segura
                'roles_id' => $rolTesorero->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Tesorero creado exitosamente.');
        $this->command->info('Email: tesorero@colegio.edu.co');
        $this->command->info('Contraseña: tesorero123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}
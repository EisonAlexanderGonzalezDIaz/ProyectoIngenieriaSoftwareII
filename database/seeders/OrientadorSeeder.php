<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class OrientadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Orientador
        $rolOrientador = RolesModel::where('nombre', 'Orientador')->first();

        if (!$rolOrientador) {
            $this->command->error('El rol de Orientador no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario orientador
        User::updateOrCreate(
            ['email' => 'orientador@colegio.edu.co'],
            [
                'name' => 'Orientador',
                'email' => 'orientador@colegio.edu.co',
                'password' => Hash::make('orient123'), // Cambiar por una contraseña segura
                'roles_id' => $rolOrientador->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Orientador creado exitosamente.');
        $this->command->info('Email: orientador@colegio.edu.co');
        $this->command->info('Contraseña: orient123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}
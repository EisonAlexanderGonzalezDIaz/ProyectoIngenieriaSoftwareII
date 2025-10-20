<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el rol de Estudiante
        $rolEstudiante = RolesModel::where('nombre', 'Estudiante')->first();

        if (!$rolEstudiante) {
            $this->command->error('El rol de Estudiante no existe. Ejecuta primero RolesSeeder.');
            return;
        }

        // Crear usuario estudiante
        User::updateOrCreate(
            ['email' => 'estudiante@colegio.edu.co'],
            [
                'name' => 'Estudiante',
                'email' => 'estudiante@colegio.edu.co',
                'password' => Hash::make('est123'), // Cambiar por una contraseña segura
                'roles_id' => $rolEstudiante->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario Estudiante creado exitosamente.');
        $this->command->info('Email: estudiante@colegio.edu.co');
        $this->command->info('Contraseña: est123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
    }
}
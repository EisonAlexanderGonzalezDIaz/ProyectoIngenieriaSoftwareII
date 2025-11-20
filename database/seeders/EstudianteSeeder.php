<?php

namespace Database\Seeders;

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
        // Obtener el rol de Estudiante
        $rolEstudiante = RolesModel::where('nombre', 'Estudiante')->first();

        if (!$rolEstudiante) {
            $this->command->error('Rol Estudiante no encontrado. Ejecute RolesSeeder primero.');
            return;
        }

        // Obtener cursos disponibles
        $cursos = \App\Models\Curso::all();
        if ($cursos->isEmpty()) {
            $this->command->error('No hay cursos disponibles. Ejecute CursosSeeder primero.');
            return;
        }

        // Crear estudiantes de ejemplo (10 estudiantes)
        $nombres = [
            'Juan Pablo Martínez',
            'María José García',
            'Carlos Alberto López',
            'Ana María Rodríguez',
            'Luis Fernando Pérez',
            'Sofía Daniela Torres',
            'Miguel Ángel Sánchez',
            'Valentina Gómez',
            'Diego Alejandro Flores',
            'Catalina Mendoza',
        ];

        $estudiantes_creados = [];

        foreach ($nombres as $index => $nombre) {
            $email = 'estudiante' . ($index + 1) . '@colegio.edu.co';
            $curso = $cursos->random();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nombre,
                    'password' => Hash::make('estudiante123'),
                    'roles_id' => $rolEstudiante->id,
                    'email_verified_at' => now(),
                    'activo' => true,
                    'curso_id' => $curso->id,
                ]
            );

            $estudiantes_creados[] = $user->id;
        }

        $this->command->info('✅ Estudiantes creados exitosamente.');
        $this->command->info('   Total: ' . count($estudiantes_creados) . ' estudiantes');
        $this->command->info('   Contraseña: estudiante123');
    }
}

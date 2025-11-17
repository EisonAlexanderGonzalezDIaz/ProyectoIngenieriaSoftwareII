<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RolesModel;
use App\Models\Curso;

class EstudiantesPorCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscar el rol "Estudiante"
        $rolEstudiante = RolesModel::where('nombre', 'Estudiante')->first();

        if (!$rolEstudiante) {
            $this->command->error('No se encontró el rol "Estudiante" en la tabla roles.');
            return;
        }

        // 2. Password genérico para todos los estudiantes de prueba
        $passwordHash = Hash::make('estudiante123'); // contraseña: estudiante123

        // 3. Obtener todos los cursos existentes
        $cursos = Curso::orderBy('nombre', 'asc')->get();

        if ($cursos->isEmpty()) {
            $this->command->error('No hay cursos en la tabla cursos. Ejecuta primero CursosBasicosSeeder.');
            return;
        }

        foreach ($cursos as $curso) {
            // Creamos 10 estudiantes por curso
            for ($i = 1; $i <= 10; $i++) {

                $numero = str_pad($i, 2, '0', STR_PAD_LEFT); // 01, 02, ..., 10

                $name  = "Est {$curso->nombre} - {$numero}";
                $email = "est{$curso->nombre}{$numero}@colegio.test";

                // Evitar duplicados si lo corres más de una vez
                if (User::where('email', $email)->exists()) {
                    continue;
                }

                User::create([
                    'name'      => $name,
                    'email'     => $email,
                    'password'  => $passwordHash,
                    'roles_id'  => $rolEstudiante->id,
                    'curso_id'  => $curso->id,
                    'activo'    => 1,                 // habilitado
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            }
        }

        $this->command->info('✅ Estudiantes de prueba creados (10 por curso).');
    }
}

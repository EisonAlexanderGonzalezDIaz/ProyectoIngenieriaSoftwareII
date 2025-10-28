<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden correcto
        $this->call([
            RolesSeeder::class,                      // Primero crear los roles
            CursosSeeder::class,                     // Cursos disponibles para matrícula
            RectorSeeder::class,                     // Usuario rector
            AdministradorSistemaSeeder::class,       // Usuario administrador del sistema
            CoordinadorAcademicoSeeder::class,       // Usuario coordinador académico
            CoordinadorDisciplinarioSeeder::class,   // Usuario coordinador disciplinario
            AcudienteSeeder::class,                  // Usuario acudiente
            EstudianteSeeder::class,                 // Usuario estudiante
            DocenteSeeder::class,                    // Usuario docente
            OrientadorSeeder::class,                 // Usuario orientador
            TesoreroSeeder::class,                   // Usuario tesorero
        ]);

        // Mensaje final informativo
        $this->command->info('✓ Base de datos poblada exitosamente con todos los roles y usuarios.');
    }
}

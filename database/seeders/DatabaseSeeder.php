<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            RolesSeeder::class,      // Primero crear los roles
            RectorSeeder::class,     // Luego crear el usuario rector
            AdministradorSistemaSeeder::class, // Luego crear el usuario admin sistema
            CoordinadorAcademicoSeeder::class, // Luego crear el usuario coordinador academico
            CoordinadorDisciplinarioSeeder::class, // Luego crear el usuario coordinador disciplinario
            AcudienteSeeder::class,  // Luego crear el usuario coordinador academico
            EstudianteSeeder::class, // Luego crear el usuario estudiante
            DocenteSeeder::class,    // Luego crear el usuario  docente
            OrientadorSeeder::class,   // Luego crear el usuario orientador
            TesoreroSeeder::class,   // fianlmente crear el usuario tesorero


        ]);
        
        // Comentado el usuario de prueba para evitar conflictos
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosBasicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de cursos que queremos asegurar que existan
        $cursos = [
            // Primaria
            ['nombre' => '1A',  'descripcion' => 'Grado 1 - Grupo A'],
            ['nombre' => '1B',  'descripcion' => 'Grado 1 - Grupo B'],
            ['nombre' => '2A',  'descripcion' => 'Grado 2 - Grupo A'],
            ['nombre' => '2B',  'descripcion' => 'Grado 2 - Grupo B'],
            ['nombre' => '3A',  'descripcion' => 'Grado 3 - Grupo A'],
            ['nombre' => '3B',  'descripcion' => 'Grado 3 - Grupo B'],
            ['nombre' => '4A',  'descripcion' => 'Grado 4 - Grupo A'],
            ['nombre' => '4B',  'descripcion' => 'Grado 4 - Grupo B'],
            ['nombre' => '5A',  'descripcion' => 'Grado 5 - Grupo A'],
            ['nombre' => '5B',  'descripcion' => 'Grado 5 - Grupo B'],

            // Ya tenías 6A, 6B y 10A en la BD,
            // pero los dejamos igual por si acaso:
            ['nombre' => '6A',  'descripcion' => 'Grado 6 - Grupo A'],
            ['nombre' => '6B',  'descripcion' => 'Grado 6 - Grupo B'],

            // Faltantes de bachillerato
            ['nombre' => '7A',  'descripcion' => 'Grado 7 - Grupo A'],
            ['nombre' => '7B',  'descripcion' => 'Grado 7 - Grupo B'],
            ['nombre' => '8A',  'descripcion' => 'Grado 8 - Grupo A'],
            ['nombre' => '8B',  'descripcion' => 'Grado 8 - Grupo B'],
            ['nombre' => '9A',  'descripcion' => 'Grado 9 - Grupo A'],
            ['nombre' => '9B',  'descripcion' => 'Grado 9 - Grupo B'],
            ['nombre' => '10A', 'descripcion' => 'Grado 10 - Grupo A'],
            ['nombre' => '10B', 'descripcion' => 'Grado 10 - Grupo B'],
            ['nombre' => '11A', 'descripcion' => 'Grado 11 - Grupo A'],
            ['nombre' => '11B', 'descripcion' => 'Grado 11 - Grupo B'],
        ];

        foreach ($cursos as $data) {
            // No duplica si ya existe un curso con ese nombre
            Curso::firstOrCreate(
                ['nombre' => $data['nombre']],
                ['descripcion' => $data['descripcion']]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolesModel;
use App\Models\User;
use App\Models\CasoDisciplinario;
use App\Models\Cita;
use Illuminate\Support\Facades\Hash;

class OrientadorFlowSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o recuperar rol Orientador
        $rol = RolesModel::firstOrCreate(['nombre' => 'Orientador'], ['descripcion' => 'Orientador del colegio']);

        // Crear orientador
        $orientador = User::firstOrCreate(
            ['email' => 'orientador@example.com'],
            [
                'name' => 'Orientador Ejemplo',
                'password' => Hash::make('password'),
                'roles_id' => $rol->id,
                'activo' => 1,
            ]
        );

        // Crear 2 estudiantes de ejemplo
        $est1 = User::firstOrCreate(
            ['email' => 'estudiante1@example.com'],
            ['name' => 'Estudiante Uno', 'password' => Hash::make('password'), 'roles_id' => null, 'activo' => 1]
        );

        $est2 = User::firstOrCreate(
            ['email' => 'estudiante2@example.com'],
            ['name' => 'Estudiante Dos', 'password' => Hash::make('password'), 'roles_id' => null, 'activo' => 1]
        );

        // Crear algunos casos
        for ($i = 1; $i <= 3; $i++) {
            CasoDisciplinario::firstOrCreate(
                ['codigo' => 'CAS' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'estudiante_id' => ($i % 2 == 0) ? $est2->id : $est1->id,
                    'encargado_id' => $orientador->id,
                    'tipo' => ['Convivencia', 'Académico', 'Emocional'][($i-1) % 3],
                    'riesgo' => ['Alto', 'Medio', 'Bajo'][($i-1) % 3],
                    'descripcion' => 'Caso de ejemplo #' . $i,
                    'fecha_apertura' => now()->subDays(10 - $i)->toDateString(),
                    'estado' => ($i === 3) ? 'Cerrado' : 'En seguimiento',
                ]
            );
        }

        // Crear algunas citas
        Cita::firstOrCreate([
            'estudiante_id' => $est1->id,
            'orientador_id' => $orientador->id,
            'fecha' => now()->toDateString(),
            'hora' => '09:00',
        ], ['motivo' => 'Seguimiento Caso CAS001', 'estado' => 'confirmado']);

        Cita::firstOrCreate([
            'estudiante_id' => $est2->id,
            'orientador_id' => $orientador->id,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:30',
        ], ['motivo' => 'Apoyo académico', 'estado' => 'solicitado']);
    }
}

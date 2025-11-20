<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use App\Models\Pago;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando DemoDataSeeder — creando roles y usuarios de prueba');

        $roles = [
            'Acudiente', 'Docente', 'Tesorero', 'CoordinadorAcademico', 'CoordinadorDisciplinario', 'Orientador'
        ];

        $roleIds = [];
        foreach ($roles as $r) {
            $role = RolesModel::firstOrCreate(
                ['nombre' => $r],
                ['descripcion' => "Rol de prueba: {$r}"]
            );
            $roleIds[$r] = $role->id;
        }

        // Crear un acudiente de prueba
        $acudiente = User::updateOrCreate(
            ['email' => 'acudiente.demo@colegio.test'],
            [
                'name' => 'Acudiente Demo',
                'password' => Hash::make('demo1234'),
                'roles_id' => $roleIds['Acudiente'],
                'email_verified_at' => now(),
            ]
        );

        // Crear 2 estudiantes y vincularlos al acudiente
        $est1 = User::updateOrCreate(
            ['email' => 'estudiante1.demo@colegio.test'],
            [
                'name' => 'Estudiante Demo 1',
                'password' => Hash::make('demo1234'),
                'roles_id' => RolesModel::where('nombre', 'Estudiante')->first()->id ?? null,
                'email_verified_at' => now(),
            ]
        );

        $est2 = User::updateOrCreate(
            ['email' => 'estudiante2.demo@colegio.test'],
            [
                'name' => 'Estudiante Demo 2',
                'password' => Hash::make('demo1234'),
                'roles_id' => RolesModel::where('nombre', 'Estudiante')->first()->id ?? null,
                'email_verified_at' => now(),
            ]
        );

        // Vincular acudiente ↔ estudiantes (tabla pivote 'acudiente_estudiante')
        try {
            $acudiente->estudiantesACargo()->syncWithoutDetaching([$est1->id, $est2->id]);
        } catch (\Throwable $e) {
            // Si la tabla pivote no existe aún, ignorar y continuar
            $this->command->warn('No se pudo vincular acudientes (pivote ausente): ' . $e->getMessage());
        }

        // Crear Docente demo
        $docente = User::updateOrCreate(
            ['email' => 'docente.demo@colegio.test'],
            [
                'name' => 'Docente Demo',
                'password' => Hash::make('demo1234'),
                'roles_id' => $roleIds['Docente'],
                'email_verified_at' => now(),
            ]
        );

        // Crear Tesorero demo
        $tesorero = User::updateOrCreate(
            ['email' => 'tesorero.demo@colegio.test'],
            [
                'name' => 'Tesorero Demo',
                'password' => Hash::make('demo1234'),
                'roles_id' => $roleIds['Tesorero'],
                'email_verified_at' => now(),
            ]
        );

        // Crear Coordinadores y Orientador
        User::updateOrCreate(
            ['email' => 'coordacademico.demo@colegio.test'],
            ['name' => 'Coordinador Académico Demo', 'password' => Hash::make('demo1234'), 'roles_id' => $roleIds['CoordinadorAcademico'], 'email_verified_at' => now()]
        );

        User::updateOrCreate(
            ['email' => 'coorddiscip.demo@colegio.test'],
            ['name' => 'Coordinador Disciplinario Demo', 'password' => Hash::make('demo1234'), 'roles_id' => $roleIds['CoordinadorDisciplinario'], 'email_verified_at' => now()]
        );

        User::updateOrCreate(
            ['email' => 'orientador.demo@colegio.test'],
            ['name' => 'Orientador Demo', 'password' => Hash::make('demo1234'), 'roles_id' => $roleIds['Orientador'], 'email_verified_at' => now()]
        );

        // Crear un pago demo para el acudiente
        try {
            Pago::create([
                'acudiente_id' => $acudiente->id,
                'monto' => 120000.00,
                'tipo' => 'factura',
                'descripcion' => 'Pago demo generado por DemoDataSeeder',
                'estado' => 'pendiente',
            ]);
        } catch (\Throwable $e) {
            $this->command->warn('No se pudo crear Pago demo: ' . $e->getMessage());
        }

        // Crear notificación demo
        try {
            Notificacion::create([
                'user_id' => $acudiente->id,
                'titulo' => 'Notificación demo',
                'descripcion' => 'Esta es una notificación creada por DemoDataSeeder',
                'tipo' => 'evento',
                'leida' => false,
            ]);
        } catch (\Throwable $e) {
            $this->command->warn('No se pudo crear Notificación demo: ' . $e->getMessage());
        }

        $this->command->info('DemoDataSeeder completado. Usuarios demo creados (contraseña: demo1234)');
    }
}

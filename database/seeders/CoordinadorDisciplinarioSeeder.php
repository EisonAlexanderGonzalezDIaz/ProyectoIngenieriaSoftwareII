<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class CoordinadorDisciplinarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ⚖️ Permisos específicos del rol Coordinador Disciplinario
        $permisos = [
            // Estudiantes
            'ver_estudiantes',
            'registrar_reportes_disciplinarios',
            'editar_reportes_disciplinarios',
            'ver_historial_disciplinario',
            'gestionar_inasistencias',

            // Comunicación
            'enviar_comunicados',
            'ver_comunicados',
            'comunicarse_acudientes',

            // Académico
            'ver_notas',
            'ver_historial_academico',

            // Gestión
            'generar_informes_disciplina',
            'aprobar_justificaciones_inasistencias',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // 🧩 Asegurar que el rol Coordinador Disciplinario exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'CoordinadorDisciplinario'],
            [
                'descripcion' => 'Responsable del control disciplinario, convivencia escolar y seguimiento del comportamiento estudiantil.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos si el rol ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👤 Crear o actualizar el usuario Coordinador Disciplinario
        $user = User::updateOrCreate(
            ['email' => 'coordinadordisciplinario@colegio.edu.co'],
            [
                'name' => 'Coordinador Disciplinario',
                'password' => Hash::make('coordisc123'), // 🔒 Cambiar después del primer login
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes en consola
        $this->command?->info('✅ Usuario Coordinador Disciplinario creado o actualizado correctamente.');
        $this->command?->info('   Email: coordinadordisciplinario@colegio.edu.co');
        $this->command?->info('   Contraseña: coordisc123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}

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
            'asignar_grupos',
            'editar_estudiantes',
            'ver_historial_academico',

            // Docentes y Asignaturas
            'ver_docentes',
            'asignar_asignaturas',
            'ver_horarios',
            'editar_horarios',

            // Académico
            'ver_estudiantes',
            'gestionar_docentes',
            'gestionar_horarios',
            'gestionar_materias',
            'aprobar_cambios_notas',
            'gestionar_recuperaciones',
            'citar_acudientes',
            'generar_reportes_academicos',

            // Disciplina
            'ver_estudiantes',
            'recibir_casos_disciplinarios',
            'reportar_casos_disciplinarios',
            'revisar_casos_graves',
            'asignar_sanciones',
            'citar_acudientes',
            'generar_reportes_disciplinarios',

            // Comunicación
            'enviar_comunicados',
            'ver_comunicados',

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

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
        // 🎓 Permisos específicos del rol Estudiante
        $permisos = [
            // Académico
            'ver_notas',
            'ver_horario_clases',
            'ver_materias_asignadas',
            'ver_calendario_academico',

            // Comunicación
            'recibir_comunicados',
            'ver_mensajes_docentes',
            'responder_mensajes',

            // Asistencia y seguimiento
            'ver_asistencia',
            'ver_reporte_asistencia',

            // Material de estudio
            'descargar_material_clase',
            'ver_tareas_asignadas',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // 🧩 Asegurar que el rol Estudiante exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'Estudiante'],
            [
                'descripcion' => 'Rol asignado a los alumnos matriculados en el sistema académico del colegio.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos si ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👩‍🎓 Crear o actualizar usuario Estudiante
        $user = User::updateOrCreate(
            ['email' => 'estudiante@colegio.edu.co'],
            [
                'name' => 'Estudiante',
                'password' => Hash::make('est123'), // 🔒 Cambiar después del primer inicio de sesión
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes en consola
        $this->command?->info('✅ Usuario Estudiante creado o actualizado correctamente.');
        $this->command?->info('   Email: estudiante@colegio.edu.co');
        $this->command?->info('   Contraseña: estudiante123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}

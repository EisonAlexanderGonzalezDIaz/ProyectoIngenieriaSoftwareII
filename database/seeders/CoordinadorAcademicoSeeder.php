<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class CoordinadorAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 📚 Permisos específicos para el rol Coordinador Académico
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
            'ver_reportes_disciplinarios',
            'gestionar_inasistencias',

            // Comunicación
            'enviar_comunicados',
            'ver_comunicados',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // ⚙️ Asegurar que el rol Coordinador Académico exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'CoordinadorAcademico'],
            [
                'descripcion' => 'Responsable de la gestión académica y seguimiento docente del colegio.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos si el rol ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👤 Crear o actualizar el usuario Coordinador Académico
        $user = User::updateOrCreate(
            ['email' => 'coordinador@colegio.edu.co'],
            [
                'name' => 'Coordinador Académico',
                'password' => Hash::make('cooracad123'), // 🔒 Cambiar después del primer login
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes en consola
        $this->command?->info('✅ Usuario Coordinador Académico creado o actualizado correctamente.');
        $this->command?->info('   Email: coordinador@colegio.edu.co');
        $this->command?->info('   Contraseña: cooracad123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}

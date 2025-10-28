<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 📚 Permisos específicos del rol Docente
        $permisos = [
            // Académico
            'ver_estudiantes_asignados',
            'registrar_notas',
            'editar_notas',
            'ver_historial_academico',
            'subir_material_clase',

            // Comunicación
            'comunicarse_acudientes',
            'enviar_comunicados',
            'ver_comunicados',

            // Evaluación y asistencia
            'registrar_asistencia',
            'editar_asistencia',
            'ver_reporte_asistencia',

            // Reportes y seguimiento
            'ver_informes_estudiantes',
            'generar_reportes_academicos',

            // Perfil propio
            'ver_perfil_propio',
            'editar_perfil_propio',
            'cambiar_contrasena',
        ];

        // 🧩 Asegurar que el rol Docente exista
        $rol = RolesModel::firstOrCreate(
            ['nombre' => 'Docente'],
            [
                'descripcion' => 'Responsable de la enseñanza, evaluación y seguimiento académico de los estudiantes.',
                'permisos' => $permisos,
            ]
        );

        // 🔄 Actualizar permisos del rol si ya existía
        $rol->permisos = $permisos;
        $rol->save();

        // 👨‍🏫 Crear o actualizar el usuario Docente
        $user = User::updateOrCreate(
            ['email' => 'docente@colegio.edu.co'],
            [
                'name' => 'Docente',
                'password' => Hash::make('doc123'), // 🔒 Cambiar después del primer login
                'roles_id' => $rol->id,
                'email_verified_at' => now(),
            ]
        );

        // 🧾 Mensajes informativos en consola
        $this->command?->info('✅ Usuario Docente creado o actualizado correctamente.');
        $this->command?->info('   Email: docente@colegio.edu.co');
        $this->command?->info('   Contraseña: doc123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');
    }
}

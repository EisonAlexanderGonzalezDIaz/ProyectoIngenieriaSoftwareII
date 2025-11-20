<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RolesModel;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente; // 👈 AÑADIMOS EL MODELO DOCENTE

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
        $this->command?->info('   Contraseña: docente123');
        $this->command?->warn('⚠️ Cambia la contraseña después del primer inicio de sesión.');

        // ===============================
        // 👨‍🏫 CREAR DOCENTES EN LA TABLA
        // ===============================
        $docentes = [
            [
                'nombre_completo' => 'Prof. García',
                'email' => 'garcia@colegio.edu.co',
                'telefono' => '3001112233',
            ],
            [
                'nombre_completo' => 'Prof. López',
                'email' => 'lopez@colegio.edu.co',
                'telefono' => '3001112234',
            ],
            [
                'nombre_completo' => 'Prof. Martínez',
                'email' => 'martinez@colegio.edu.co',
                'telefono' => '3001112235',
            ],
            [
                'nombre_completo' => 'Prof. Rodríguez',
                'email' => 'rodriguez@colegio.edu.co',
                'telefono' => '3001112236',
            ],
            [
                'nombre_completo' => 'Prof. Pérez',
                'email' => 'perez@colegio.edu.co',
                'telefono' => '3001112237',
            ],
            [
                'nombre_completo' => 'Prof. Suárez',
                'email' => 'suarez@colegio.edu.co',
                'telefono' => '3001112238',
            ],
            [
                'nombre_completo' => 'Prof. Castillo',
                'email' => 'castillo@colegio.edu.co',
                'telefono' => '3001112239',
            ],
            [
                'nombre_completo' => 'Prof. Torres',
                'email' => 'torres@colegio.edu.co',
                'telefono' => '3001112240',
            ],
            [
                'nombre_completo' => 'Prof. Herrera',
                'email' => 'herrera@colegio.edu.co',
                'telefono' => '3001112241',
            ],
            [
                'nombre_completo' => 'Prof. Rojas',
                'email' => 'rojas@colegio.edu.co',
                'telefono' => '3001112242',
            ],
            [
                'nombre_completo' => 'Prof. Medina',
                'email' => 'medina@colegio.edu.co',
                'telefono' => '3001112243',
            ],
            [
                'nombre_completo' => 'Prof. Vargas',
                'email' => 'vargas@colegio.edu.co',
                'telefono' => '3001112244',
            ],
            [
                'nombre_completo' => 'Prof. Jiménez',
                'email' => 'jimenez@colegio.edu.co',
                'telefono' => '3001112245',
            ],
            [
                'nombre_completo' => 'Prof. Rivera',
                'email' => 'rivera@colegio.edu.co',
                'telefono' => '3001112246',
            ],
            [
                'nombre_completo' => 'Prof. Delgado',
                'email' => 'delgado@colegio.edu.co',
                'telefono' => '3001112247',
            ],
            [
                'nombre_completo' => 'Prof. Castaño',
                'email' => 'castano@colegio.edu.co', // sin tilde
                'telefono' => '3001112248',
            ],
            [
                'nombre_completo' => 'Prof. León',
                'email' => 'leon@colegio.edu.co', // sin tilde
                'telefono' => '3001112249',
            ],
            [
                'nombre_completo' => 'Prof. Rubio',
                'email' => 'rubio@colegio.edu.co',
                'telefono' => '3001112250',
            ],
            [
                'nombre_completo' => 'Prof. Gonzalez',
                'email' => 'gonzalez@colegio.edu.co',
                'telefono' => '3001112251',
            ],
        ];

        foreach ($docentes as $docenteData) {
            Docente::updateOrCreate(
                ['nombre_completo' => $docenteData['nombre_completo']], // criterio único
                [
                    'email' => $docenteData['email'],
                    'telefono' => $docenteData['telefono'],
                ]
            );
        }

        $this->command?->info('✅ Docentes creados o verificados en la tabla docentes.');
    }
}

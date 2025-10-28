<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolesModel;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            // 🔹 Administrador del Sistema
            [
                'nombre' => 'AdministradorSistema',
                'descripcion' => 'Encargado de la administración técnica del sistema educativo.',
                'permisos' => [
                    'acceso_total',
                    'gestionar_usuarios',
                    'gestionar_roles_y_permisos',
                    'configurar_sistema',
                    'realizar_copias_de_seguridad',
                    'restaurar_sistema',
                    'monitorear_rendimiento',
                    'actualizar_sistema',
                    'ver_logs_sistema',
                    'gestionar_informacion_institucional'
                ]
            ],

            // 🔹 Rector
            [
                'nombre' => 'Rector',
                'descripcion' => 'Máxima autoridad del colegio, responsable de la dirección y administración general.',
                'permisos' => [
                    'gestionar_usuarios',
                    'gestionar_docentes',
                    'gestionar_estudiantes',
                    'gestionar_coordinadores',
                    'gestionar_orientador',
                    'gestionar_tesoreria',
                    'ver_reportes_generales',
                    'ver_reportes_financieros',
                    'aprobar_matriculas',
                    'aprobar_becas_y_descuentos',
                    'revisar_casos_graves',
                    'asignar_sanciones',
                    'enviar_comunicados',
                    'publicar_reglamentos',
                    'consultar_informacion_colegio'
                ]
            ],

            // 🔹 Coordinador Académico
            [
                'nombre' => 'CoordinadorAcademico',
                'descripcion' => 'Encargado de coordinar actividades académicas y pedagógicas.',
                'permisos' => [
                    'ver_estudiantes',
                    'ver_docentes',
                    'gestionar_materias',
                    'gestionar_horarios',
                    'gestionar_periodos',
                    'aprobar_notas',
                    'ver_notas',
                    'ver_historial_academico',
                    'ver_reportes_academicos',
                    'generar_reportes',
                    'comunicarse_con_acudientes'
                ]
            ],

            // 🔹 Coordinador Disciplinario
            [
                'nombre' => 'CoordinadorDisciplinario',
                'descripcion' => 'Encargado de coordinar actividades disciplinarias y de convivencia escolar.',
                'permisos' => [
                    'ver_estudiantes',
                    'gestionar_disciplina',
                    'ver_reportes_disciplinarios',
                    'asignar_sanciones',
                    'revisar_casos_graves',
                    'gestionar_asistencia',
                    'enviar_comunicados',
                    'comunicarse_con_acudientes',
                    'ver_reportes_academicos'
                ]
            ],

            // 🔹 Docente
            [
                'nombre' => 'Docente',
                'descripcion' => 'Profesor encargado de impartir clases y evaluar estudiantes.',
                'permisos' => [
                    'ver_estudiantes',
                    'registrar_notas',
                    'ver_notas',
                    'crear_actividades',
                    'ver_horarios',
                    'gestionar_asistencia',
                    'comunicarse_acudientes',
                    'ver_reportes_academicos',
                    'ver_perfil_propio',
                    'editar_perfil_propio',
                    'cambiar_contrasena',
                    'ver_notificaciones'
                ]
            ],

            // 🔹 Estudiante
            [
                'nombre' => 'Estudiante',
                'descripcion' => 'Estudiante del colegio.',
                'permisos' => [
                    'ver_notas',
                    'ver_horarios',
                    'ver_actividades',
                    'ver_comunicados',
                    'consultar_plan_estudios',
                    'ver_perfil_propio',
                    'editar_perfil_propio',
                    'cambiar_contrasena',
                    'ver_notificaciones'
                ]
            ],

            // 🔹 Acudiente
            [
                'nombre' => 'Acudiente',
                'descripcion' => 'Padre, madre o acudiente responsable del estudiante.',
                'permisos' => [
                    'ver_estudiantes',
                    'ver_historial_academico',
                    'ver_notas',
                    'ver_horarios',
                    'comunicarse_docentes',
                    'ver_reportes_academicos',
                    'ver_reportes_disciplinarios',
                    'justificar_inasistencias',
                    'ver_pagos',
                    'ver_perfil_propio',
                    'editar_perfil_propio',
                    'cambiar_contrasena',
                    'ver_notificaciones'
                ]
            ],

            // 🔹 Orientador
            [
                'nombre' => 'Orientador',
                'descripcion' => 'Profesional encargado de la orientación y apoyo psicológico de los estudiantes.',
                'permisos' => [
                    'gestionar_citas',
                    'asesorar_estudiantes',
                    'asesorar_docentes_y_acudientes',
                    'registrar_informes_psicologicos',
                    'gestionar_programas_orientacion',
                    'gestionar_casos_graves'
                ]
            ],

            // 🔹 Tesorero
            [
                'nombre' => 'Tesorero',
                'descripcion' => 'Responsable de la gestión financiera del colegio.',
                'permisos' => [
                    'gestionar_paz_y_salvo',
                    'gestionar_pagos_matriculas',
                    'gestionar_facturacion',
                    'gestionar_devoluciones',
                    'gestionar_carteras',
                    'gestionar_becas_y_descuentos',
                    'consultar_estado_cuentas',
                    'ver_reportes_financieros'
                ]
            ],
        ];

        foreach ($roles as $rol) {
            RolesModel::updateOrCreate(
                ['nombre' => $rol['nombre']],
                $rol
            );
        }

        $this->command?->info('✅ Roles y permisos actualizados correctamente.');
    }
}

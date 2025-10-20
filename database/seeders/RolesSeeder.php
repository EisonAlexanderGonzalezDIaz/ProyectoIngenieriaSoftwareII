<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'nombre' => 'Rector',
                'descripcion' => 'Máxima autoridad del colegio, responsable de la dirección y administración general',
                'permisos' => [
                    'aprobar_becas_y_descuentos',
                    'consultar_informacion_colegio',
                    'publicar_reglamentos_y_normas',
                    'registrar_informacion_institucional',
                    'asignar_sanciones',
                    'revisar_casos_graves',
                    'aprobar_matriculas',
                    'gestionar_docentes',
                    'gestionar_estudiantes',
                    'gestionar_coordinadores',
                    'gestionar_acudientes',
                    'gesrionar_tesoreria',
                    'gestionar_orientador',
                    
                ]
            ],
            [
                'nombre' => 'CoordinadorAcademico',
                'descripcion' => 'Encargado de coordinar actividades académicas',
                'permisos' => [
                    'gestionar_estudiantes',
                    'gestionar_docentes',
                    'gestionar_materias',
                    'generar_reportes_académicos',
                    'gestionar_horarios',
                    'aprobar_cambios_de_notas',
                    'comunicarse_con_acudientes',

                ]
            ],
                        [
                'nombre' => 'CoordinadorDisciplinario',
                'descripcion' => 'Encargado de coordinar actividades disciplinarias',
                'permisos' => [
                    'gestionar_estudiantes',
                    'gestionar_docentes',
                    'generar_reportes_disciplinarios',
                    'comunicarse_con_acudientes',
                    'reportar_casos_disciplinarios',
                    'asignar_sanciones',
                    'revisar_casos_graves',

                ]
            ],
            [
                'nombre' => 'Docente',
                'descripcion' => 'Profesor encargado de impartir clases y evaluar estudiantes',
                'permisos' => [
                    'ver_estudiantes_asignados',
                    'gestionar_notas',
                    'gestionar_horarios',
                    'comunicarse_acudientes',
                    'gestionar_materias',
                    'solicitar_citas_orientador',
                    'registrar_asistencias',
                    'gestionar_boletines',
                    'reportar_casos_disciplinarios',

                ]
            ],
            [
                'nombre' => 'Estudiante',
                'descripcion' => 'Estudiante del colegio',
                'permisos' => [
                    'consultar_notas',
                    'consultar_horarios',
                    'consutar_materias',
                    'actualizar_perfil_básico',
                    'solicitar_citas_orientador',
                    'consultar_plan_de_estudios',
                    'consutar_material_academico',
                    'solicitar_certificados',
                    'recibir_reportes_academicos',
                    'recibir_reportes_disciplinarios',
                    'consultar_informacion_colegio',

                ]
            ],
            [
                'nombre' => 'Acudiente',
                'descripcion' => 'Padre, madre o acudiente responsable del estudiante',
                'permisos' => [
                    'ver_notas_estudiante',
                    'ver_horarios_estudiante',
                    'comunicarse_docentes',
                    'ver_reportes_estudiante',
                    'actualizar_datos_contacto',
                    'justificar_inasistencias'
                ]
           ],

           [
                'nombre' => 'Orientador',
                'descripcion' => 'Profesional encargado de la orientación y apoyo psicológico de los estudiantes',
                'permisos' => [
                    'gestionar_citas',
                    'asesorar_estudiantes',
                    'asesorar_docentes_y_acudientes',
                    'registrar_informes_psicologicos',
                    'gestionar_programas_de_orientacion',
                    'gestionar_casos_graves',

                ]
            ],

            [
                'nombre' => 'Tesorero',
                'descripcion' => 'Responsable de la gestión financiera del colegio',
                'permisos' => [
                    'gsetionar_paz_y_salvo',
                    'gestionar_pagos_matriculas',
                    'gestionar_facturacion',
                    'gestionar_devoluciones',
                    'gestionar_carteras',
                    'generar_reportes_financieros',
                    'gestionar_becas_y_descuentos',
                    'consultar_estado_de_cuentas',
                ]
            ],  
            
            [
                'nombre' => 'AdministradorSistema',
                'descripcion' => 'Encargado de la administración y mantenimiento del sistema',
                'permisos' => [
                    'gestionar_usuarios',
                    'gestionar_roles_y_permisos',
                    'realizar_copias_de_seguridad',
                    'restaurar_sistema',
                    'monitorear_rendimiento_del_sistema',
                    'actualizar_sistema',
                    'gestionar_informacion_institucional',
                ]
            ],
        ];

        foreach ($roles as $rol) {
            RolesModel::updateOrCreate(
                ['nombre' => $rol['nombre']],
                $rol
            );
        }
    }
}
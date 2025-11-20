<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controladores principales
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\RolController;
use App\Http\Controllers\MatriculaAcudienteController;
use App\Http\Controllers\TesoreroController;

// Controladores académicos / roles
use App\Http\Controllers\AdminEstudiantesController;
use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\RectorEstudianteController;
use App\Http\Controllers\CoordinadorAcademicoController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\CambiosNotasController;
use App\Http\Controllers\RecuperacionesController;
use App\Http\Controllers\ReportesAcademicosController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\CasosDisciplinariosController;
use App\Http\Controllers\ReportesDisciplinariosController;
use App\Http\Controllers\PlanAcademicoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\RectorController;
use App\Http\Controllers\AcudienteEstudianteController;
use App\Http\Controllers\AcudienteController;
use App\Http\Controllers\GestionDocentesController;

use App\Models\RolesModel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se definen las rutas principales de la aplicación.
| Las rutas están organizadas por módulos y protegidas por middleware.
|--------------------------------------------------------------------------
*/

// Ruta raíz → redirige al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de registro de usuarios
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);

// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // ==========================
    // DASHBOARD GENERAL
    // ==========================
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // ==========================
    // Rutas Rector - Consultas académicas
    // ==========================
    Route::prefix('rector')->name('rector.')->middleware('can:isRector')->group(function () {
        Route::get('/boletines', [RectorEstudianteController::class, 'boletines'])
            ->name('boletines');

        Route::get('/notas', [RectorEstudianteController::class, 'notas'])
            ->name('notas');

        Route::get('/materias', [RectorEstudianteController::class, 'materias'])
            ->name('materias');
    });

    /*
    |--------------------------------------------------------------------------
    | Coordinador Académico
    |--------------------------------------------------------------------------
    */
    Route::prefix('coordinador-academico')->name('coordinador.')->group(function () {
        // Dashboard del coordinador académico
        Route::get('/', [CoordinadorAcademicoController::class, 'dashboard'])->name('dashboard');

        // Gestión de docentes
        Route::get('/gestion-docentes', [CoordinadorAcademicoController::class, 'gestionDocentes'])
            ->name('gestion-docentes');

        // Rutas AJAX (consultas, actualizaciones, asignaciones, evaluaciones)
        Route::get('/docentes/{id}', [CoordinadorAcademicoController::class, 'getTeacherData']);
        Route::put('/docentes/{id}', [CoordinadorAcademicoController::class, 'updateTeacher']);
        Route::post('/docentes/{id}/subjects', [CoordinadorAcademicoController::class, 'assignSubjects']);
        Route::post('/docentes/{id}/evaluations', [CoordinadorAcademicoController::class, 'addPerformanceEvaluation']);
    });

    // Ruta para obtener todas las materias (para el modal de asignación)
    Route::get('/api/subjects', [CoordinadorAcademicoController::class, 'getAllSubjects']);

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    // Página de creación / asignación de roles y permisos
    Route::get('/roles/create', function () {
        $user = auth()->user();

        // Solo Admin del Sistema o Rector
        if (!$user || !in_array(optional($user->rol)->nombre, ['AdministradorSistema', 'Rector'])) {
            abort(403, 'Solo el Administrador del Sistema o el Rector pueden gestionar roles.');
        }

        return view('roles.create');
    })->name('roles.create');

    // Creación rápida de roles
    Route::post('/roles/quick-create', function (Request $request) {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'No autenticado.');
        }

        $rolNombre = optional($user->rol)->nombre;
        if (!in_array($rolNombre, ['AdministradorSistema', 'Rector'])) {
            abort(403, 'Solo el Administrador del Sistema o el Rector pueden crear roles.');
        }

        // Validación de datos
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:100', 'unique:roles,nombre'],
            'descripcion' => ['required', 'string', 'max:500'],
            'permisos'  => ['nullable', 'string'], // CSV desde la vista
        ]);

        // Convertir CSV → Array
        $permisosArray = [];
        if (!empty($data['permisos'])) {
            $permisosArray = collect(explode(',', $data['permisos']))
                ->map(fn ($p) => trim($p))
                ->filter()
                ->values()
                ->all();
        }

        RolesModel::create([
            'nombre'    => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'permisos'  => $permisosArray ?: null,
        ]);

        return redirect()->route('roles.create')->with('ok', 'Rol creado: ' . $data['nombre']);
    })->name('roles.quick-create');

    /*
    |--------------------------------------------------------------------------
    | Tesorería
    |--------------------------------------------------------------------------
    */
    Route::prefix('tesoreria')->name('tesoreria.')->group(function () {
        // Vistas para tesorería (páginas UI)
        Route::get('/vista/paz-y-salvo', [TesoreroController::class, 'viewPazYSalvo'])->name('view.pazysalvo');
        Route::get('/vista/factura-matricula', [TesoreroController::class, 'viewGenerarFactura'])->name('view.factura.matricula');
        Route::get('/vista/registrar-pago', [TesoreroController::class, 'viewRegistrarPago'])->name('view.pago.registrar');
        Route::get('/vista/devolucion', [TesoreroController::class, 'viewDevolucion'])->name('view.devolucion');
        Route::get('/vista/cartera', [TesoreroController::class, 'viewCartera'])->name('view.cartera');
        Route::get('/vista/reportes', [TesoreroController::class, 'viewReportes'])->name('view.reportes');
        Route::get('/vista/estado-cuenta', [TesoreroController::class, 'viewEstadoCuenta'])->name('view.estado.cuenta');
        Route::get('/vista/beca', [TesoreroController::class, 'viewBeca'])->name('view.beca');
        Route::get('/vista/reporte-financiero', [TesoreroController::class, 'viewReporteFinanciero'])->name('view.reporte.financiero');
        Route::get('/vista/info-colegio', [TesoreroController::class, 'viewInfoColegio'])->name('view.info.colegio');
        // Vista para aprobar becas/descuentos
        Route::get('/vista/aprobar-becas', [TesoreroController::class, 'viewAprobarBecas'])->name('view.aprobar.becas');

        // Rutas API (JSON)
        Route::get('/paz-y-salvo/{acudiente}', [TesoreroController::class, 'generarPazYSalvo'])->name('pazysalvo');
        Route::post('/factura/matricula', [TesoreroController::class, 'generarFacturaMatricula'])->name('factura.matricula');
        Route::post('/pago/registrar', [TesoreroController::class, 'registrarPagoAcudiente'])->name('pago.registrar');
        Route::post('/devolucion', [TesoreroController::class, 'gestionarDevolucion'])->name('devolucion');
        Route::get('/cartera', [TesoreroController::class, 'gestionarCartera'])->name('cartera');
        Route::get('/reportes', [TesoreroController::class, 'entregarReportes'])->name('reportes');
        Route::get('/estado-cuenta/{acudiente}', [TesoreroController::class, 'consultarEstadoCuenta'])->name('estado.cuenta');
        Route::post('/beca', [TesoreroController::class, 'registrarBecaDescuento'])->name('beca.registrar');
        Route::get('/reporte-financiero', [TesoreroController::class, 'generarReporteFinanciero'])->name('reporte.financiero');
        Route::get('/info-colegio', [TesoreroController::class, 'consultarInformacionColegio'])->name('info.colegio');
    });

    /*
    |--------------------------------------------------------------------------
    | Estudiantes - Portal Académico
    |--------------------------------------------------------------------------
    */
    Route::prefix('estudiante')->name('estudiante.')->group(function () {
        // Dashboard principal del estudiante
        Route::get('/dashboard', [EstudianteController::class, 'index'])->name('dashboard');

        // Citas de Orientación
        Route::get('/citas', [EstudianteController::class, 'viewSolicitarCita'])->name('solicitar_cita');
        Route::post('/citas', [EstudianteController::class, 'crearCita'])->name('crear_cita');

        // Horarios
        Route::get('/horario', [EstudianteController::class, 'viewConsultarHorario'])->name('consultar_horario');
        Route::get('/api/horarios', [EstudianteController::class, 'obtenerHorarios'])->name('obtener_horarios');
        Route::get('/horario/descargar', [EstudianteController::class, 'descargarHorario'])->name('descargar_horario');

        // Notas
        Route::get('/notas', [EstudianteController::class, 'viewConsultarNotas'])->name('consultar_notas');
        Route::get('/api/notas', [EstudianteController::class, 'obtenerNotasPorMateria'])->name('obtener_notas');

        // Tareas y Entregas
        Route::get('/tareas', [EstudianteController::class, 'viewTareas'])->name('tareas');
        Route::get('/api/tareas', [EstudianteController::class, 'obtenerTareas'])->name('obtener_tareas');
        Route::post('/tareas/entregar', [EstudianteController::class, 'entregarTarea'])->name('entregar_tarea');

        // Boletines
        Route::get('/boletines', [EstudianteController::class, 'viewConsultarBoletines'])->name('consultar_boletines');
        Route::get('/api/boletines', [EstudianteController::class, 'obtenerBoletines'])->name('obtener_boletines');
        Route::get('/boletines/{id}/descargar', [EstudianteController::class, 'descargarBoletin'])->name('descargar_boletin');

        // Plan de Estudio
        Route::get('/plan-estudio', [EstudianteController::class, 'viewPlanEstudio'])->name('plan_estudio');
        Route::get('/api/plan-estudio', [EstudianteController::class, 'obtenerPlanEstudio'])->name('obtener_plan_estudio');

        // Certificaciones
        Route::get('/certificaciones', [EstudianteController::class, 'viewSolicitarCertificacion'])->name('solicitar_certificacion');
        Route::post('/certificaciones', [EstudianteController::class, 'crearCertificacion'])->name('crear_certificacion');
        Route::get('/api/certificaciones', [EstudianteController::class, 'obtenerCertificaciones'])->name('obtener_certificaciones');
        Route::get('/certificaciones/{id}/descargar', [EstudianteController::class, 'descargarCertificacion'])->name('descargar_certificacion');

        // Reportes Disciplinarios
        Route::get('/reportes-disciplinarios', [EstudianteController::class, 'viewReportesDisciplinarios'])->name('reportes_disciplinarios');
        Route::get('/api/reportes-disciplinarios', [EstudianteController::class, 'obtenerReportesDisciplinarios'])->name('obtener_reportes_disciplinarios');

        // Notificaciones
        Route::get('/notificaciones', [EstudianteController::class, 'viewNotificaciones'])->name('notificaciones');
        Route::get('/api/notificaciones', [EstudianteController::class, 'obtenerNotificaciones'])->name('obtener_notificaciones');
        Route::post('/notificaciones/{id}/leer', [EstudianteController::class, 'marcarNotificacionLeida'])->name('marcar_notificacion_leida');
    });

    /*
    |--------------------------------------------------------------------------
    | Estudiantes / Acudientes (Legacy)
    |--------------------------------------------------------------------------
    */
    Route::prefix('estudiantes')->name('estudiantes.')->group(function () {
        // Página principal de gestión de estudiantes
        Route::get('/', [EstudianteController::class, 'index'])->name('index');

        // Vista estática adicional
        Route::get('/gestion', function () {
            return view('estudiantes.gestion');
        })->name('gestion');
    });

    // ==========================
    // Admin: Vincular acudientes ↔ estudiantes (por estudiante)
    // ==========================
    Route::get('/admin/estudiantes/{estudiante}/acudientes', [AcudienteEstudianteController::class, 'index'])
        ->name('admin.estudiantes.acudientes.index');

    Route::post('/admin/estudiantes/{estudiante}/acudientes', [AcudienteEstudianteController::class, 'vincular'])
        ->name('admin.estudiantes.acudientes.vincular');

    Route::delete(
        '/admin/estudiantes/{estudiante}/acudientes/{acudiente}',
        [AcudienteEstudianteController::class, 'desvincular']
    )->name('admin.estudiantes.acudientes.desvincular');

    /*
    |--------------------------------------------------------------------------
    | Admin Sistema - Consultar Estudiantes y Perfiles de Usuario
    |--------------------------------------------------------------------------
    */
    // Menú / vista de "Consultar Estudiantes" (usa porCurso internamente)
    Route::get('/admin/estudiantes/menu', [AdminEstudiantesController::class, 'menu'])
        ->name('admin.estudiantes.menu');

    // Ver estudiantes por curso (ruta directa, útil para formularios / filtros)
    Route::get('/admin/estudiantes/por-curso', [AdminEstudiantesController::class, 'porCurso'])
        ->name('admin.estudiantes.porCurso');

    // Gestionar perfiles de usuario
    Route::get('/admin/usuarios/perfiles', [AdminUsuarioController::class, 'index'])
        ->name('admin.usuarios.perfiles');

    // Actualizar perfil de un usuario (rol, estado, curso)
    Route::put('/admin/usuarios/{id}/perfil', [AdminUsuarioController::class, 'updatePerfil'])
        ->name('admin.usuarios.perfil.update');

    // Actualizar datos básicos (nombre y email)
    Route::put('/admin/usuarios/{id}/basicos', [AdminUsuarioController::class, 'updateBasicos'])
        ->name('admin.usuarios.basicos.update');

    // Gestionar acudientes de un usuario desde perfiles de usuario
    Route::get('/admin/usuarios/{user}/acudientes', [AdminUsuarioController::class, 'editarAcudientes'])
        ->name('admin.usuarios.acudientes.edit');

    Route::post('/admin/usuarios/{user}/acudientes', [AdminUsuarioController::class, 'guardarAcudientes'])
        ->name('admin.usuarios.acudientes.store');

    /*
    |--------------------------------------------------------------------------
    | Horarios
    |--------------------------------------------------------------------------
    */
    Route::get('/horarios/gestion', [HorariosController::class, 'gestion'])->name('horarios.gestion');
    Route::post('/horarios/store', [HorariosController::class, 'store'])->name('horarios.store');
    Route::put('/horarios/{id}', [HorariosController::class, 'update'])->name('horarios.update');
    Route::delete('/horarios/{id}', [HorariosController::class, 'destroy'])->name('horarios.destroy');

    /*
    |--------------------------------------------------------------------------
    | Materias
    |--------------------------------------------------------------------------
    */
    Route::get('/materias/gestion', [MateriasController::class, 'gestion'])->name('materias.gestion');
    Route::post('/materias/store', [MateriasController::class, 'store'])->name('materias.store');
    Route::put('/materias/{id}', [MateriasController::class, 'update'])->name('materias.update');
    Route::delete('/materias/{id}', [MateriasController::class, 'destroy'])->name('materias.destroy');

    /*
    |--------------------------------------------------------------------------
    | Cambios de Notas
    |--------------------------------------------------------------------------
    */
    Route::get('/cambios-notas/gestion', [CambiosNotasController::class, 'gestion'])->name('cambios-notas.gestion');
    Route::post('/cambios-notas/{id}/aprobar', [CambiosNotasController::class, 'aprobar'])->name('cambios-notas.aprobar');
    Route::post('/cambios-notas/{id}/rechazar', [CambiosNotasController::class, 'rechazar'])->name('cambios-notas.rechazar');
    Route::get('/cambios-notas/{id}', [CambiosNotasController::class, 'ver'])->name('cambios-notas.ver');

    /*
    |--------------------------------------------------------------------------
    | Recuperaciones
    |--------------------------------------------------------------------------
    */
    Route::get('/recuperaciones/gestion', [RecuperacionesController::class, 'gestion'])->name('recuperaciones.gestion');
    Route::post('/recuperaciones/store', [RecuperacionesController::class, 'store'])->name('recuperaciones.store');
    Route::post('/recuperaciones/{id}/calificar', [RecuperacionesController::class, 'calificar'])->name('recuperaciones.calificar');
    Route::post('/recuperaciones/{id}/aprobar', [RecuperacionesController::class, 'aprobar'])->name('recuperaciones.aprobar');
    Route::post('/recuperaciones/{id}/rechazar', [RecuperacionesController::class, 'rechazar'])->name('recuperaciones.rechazar');
    Route::get('/recuperaciones/{id}', [RecuperacionesController::class, 'ver'])->name('recuperaciones.ver');

    /*
    |--------------------------------------------------------------------------
    | Reportes Académicos
    |--------------------------------------------------------------------------
    */
    Route::get('/reportes-academicos/gestion', [ReportesAcademicosController::class, 'gestion'])->name('reportes-academicos.gestion');
    Route::post('/reportes-academicos/generar', [ReportesAcademicosController::class, 'generar'])->name('reportes-academicos.generar');
    Route::get('/reportes-academicos/{id}/descargar', [ReportesAcademicosController::class, 'descargar'])->name('reportes-academicos.descargar');
    Route::post('/reportes-academicos/{id}/enviar', [ReportesAcademicosController::class, 'enviar'])->name('reportes-academicos.enviar');
    Route::delete('/reportes-academicos/{id}', [ReportesAcademicosController::class, 'eliminar'])->name('reportes-academicos.eliminar');
    Route::get('/reportes-academicos/{id}', [ReportesAcademicosController::class, 'ver'])->name('reportes-academicos.ver');

    /*
    |--------------------------------------------------------------------------
    | Orientador
    |--------------------------------------------------------------------------
    */
    Route::get('/orientador/gestion', [OrientadorController::class, 'dashboard'])
        ->name('orientacion.gestion');

    // API endpoints para Orientador
    Route::get('/api/orientador/casos', [OrientadorController::class, 'apiCasos'])->name('orientador.api.casos');
    Route::post('/api/orientador/agendar', [OrientadorController::class, 'apiAgendarSesion'])->name('orientador.api.agendar');
    Route::get('/api/orientador/agenda', [OrientadorController::class, 'apiAgenda'])->name('orientador.api.agenda');

    /*
    |--------------------------------------------------------------------------
    | Información del colegio
    |--------------------------------------------------------------------------
    */
    Route::get('/informacion/gestion', function () {
        return view('informacion.gestion');
    })->name('informacion.gestion');

    /*
    |--------------------------------------------------------------------------
    | Citas - Citar Acudientes
    |--------------------------------------------------------------------------
    */
    Route::get('/citas/gestion', function () {
        return view('citas.gestion');
    })->name('citas.gestion');

    /*
    |--------------------------------------------------------------------------
    | Casos disciplinarios
    |--------------------------------------------------------------------------
    */
    Route::get('/casos/gestion', [CasosDisciplinariosController::class, 'gestion'])
        ->name('casos.gestion');

    // API para casos disciplinarios
    Route::get('/api/casos', [CasosDisciplinariosController::class, 'apiList'])->name('api.casos.list');
    Route::post('/api/casos', [CasosDisciplinariosController::class, 'apiCreate'])->name('api.casos.create');
    Route::post('/api/casos/{id}/seguimiento', [CasosDisciplinariosController::class, 'apiSeguimiento'])->name('api.casos.seguimiento');

    /*
    |--------------------------------------------------------------------------
    | Reportes disciplinarios
    |--------------------------------------------------------------------------
    */
    Route::get('/reportes/gestion', [ReportesDisciplinariosController::class, 'gestion'])
        ->name('reportes.gestion');

    /*
    |--------------------------------------------------------------------------
    | Gestión de Docentes
    |--------------------------------------------------------------------------
    */
    Route::get('/gestiondocentes/gestion', [GestionDocentesController::class, 'gestion'])
        ->name('gestiondocentes.gestion');
    Route::post('/gestiondocentes/store', [GestionDocentesController::class, 'store'])
        ->name('gestiondocentes.store');
    Route::put('/gestiondocentes/{id}', [GestionDocentesController::class, 'update'])
        ->name('gestiondocentes.update');
    Route::delete('/gestiondocentes/{id}', [GestionDocentesController::class, 'destroy'])
        ->name('gestiondocentes.destroy');

    /*
    |--------------------------------------------------------------------------
    | Plan Académico
    |--------------------------------------------------------------------------
    */
    Route::get('/planacademico/gestion', [PlanAcademicoController::class, 'gestion'])
        ->name('planacademico.gestion');

    /*
    |--------------------------------------------------------------------------
    | Acudiente (Padres / Tutores)
    |--------------------------------------------------------------------------
    */
    Route::prefix('acudiente')->name('acudiente.')->group(function () {
        // Notificaciones
        Route::get('/notificaciones', [AcudienteController::class, 'viewNotificaciones'])->name('notificaciones');
        Route::get('/api/notificaciones', [AcudienteController::class, 'obtenerNotificaciones'])->name('obtener_notificaciones');
        Route::post('/notificaciones/{id}/leer', [AcudienteController::class, 'marcarNotificacionLeida'])->name('marcar_notificacion_leida');

        // Boletines
        Route::get('/boletines', [AcudienteController::class, 'viewBoletines'])->name('boletines');
        Route::get('/api/boletines', [AcudienteController::class, 'obtenerBoletines'])->name('obtener_boletines');
        Route::get('/boletines/{id}/descargar', [AcudienteController::class, 'descargarBoletin'])->name('descargar_boletin');

        // Reportes disciplinarios (hijos)
        Route::get('/reportes-disciplinarios', [AcudienteController::class, 'viewReportesDisciplinarios'])->name('reportes_disciplinarios');
        Route::get('/api/reportes-disciplinarios', [AcudienteController::class, 'obtenerReportesDisciplinarios'])->name('obtener_reportes_disciplinarios');

        // Solicitudes de Paz y Salvo
        Route::get('/paz-y-salvo/solicitar', [AcudienteController::class, 'viewSolicitarPaz'])->name('solicitar_paz');
        Route::post('/paz-y-salvo/solicitar', [AcudienteController::class, 'crearSolicitudPaz'])->name('crear_solicitud_paz');
    });

    // API: obtener estudiantes a cargo del acudiente (para formularios AJAX)
    Route::get('/api/acudiente/estudiantes', [AcudienteController::class, 'obtenerEstudiantes'])->name('acudiente.api.estudiantes');

    /*
    |--------------------------------------------------------------------------
    | Docente - Portal Docente
    |--------------------------------------------------------------------------
    */
    Route::prefix('docente')->name('docente.')->group(function () {
        // Solicitar cita a orientación
        Route::get('/solicitar-cita', [DocenteController::class, 'viewSolicitarCita'])->name('solicitar_cita');
        Route::post('/solicitar-cita', [DocenteController::class, 'crearCita'])->name('crear_cita');

        // Consultar y descargar horario
        Route::get('/horario', [DocenteController::class, 'viewConsultarHorario'])->name('consultar_horario');
        Route::get('/api/horarios', [DocenteController::class, 'obtenerHorarios'])->name('obtener_horarios');
        Route::get('/horario/descargar', [DocenteController::class, 'descargarHorario'])->name('descargar_horario');

        // Registrar notas
        Route::get('/notas', [DocenteController::class, 'viewRegistrarNotas'])->name('registrar_notas');
        Route::post('/notas/guardar', [DocenteController::class, 'guardarNota'])->name('guardar_nota');

        // Registrar asistencia
        Route::get('/asistencia', [DocenteController::class, 'viewRegistrarAsistencia'])->name('registrar_asistencia');
        Route::post('/asistencia/guardar', [DocenteController::class, 'guardarAsistencia'])->name('guardar_asistencia');

        // Subir material académico
        Route::get('/materiales', [DocenteController::class, 'viewSubirMaterial'])->name('subir_material');
        Route::post('/materiales/subir', [DocenteController::class, 'subirMaterial'])->name('subir_material_post');

        // Generar informe del curso
        Route::get('/informe-curso', [DocenteController::class, 'viewGenerarInforme'])->name('generar_informe');
        Route::post('/informe-curso/generar', [DocenteController::class, 'generarInforme'])->name('generar_informe_post');
        Route::get('/informe-curso/datos', [DocenteController::class, 'obtenerDatosAutorellenado'])->name('obtener_datos_autorellenado');
    });

    // API Docente endpoints
    Route::get('/api/docente/estudiantes-por-curso', [DocenteController::class, 'obtenerEstudiantesPorCurso'])->name('docente.obtener_estudiantes_por_curso');
    Route::get('/api/docente/materias-por-curso', [DocenteController::class, 'obtenerMateriasXCurso'])->name('docente.materias_por_curso');
    Route::get('/api/docente/materiales', [DocenteController::class, 'obtenerMateriales'])->name('docente.obtener_materiales');
    Route::get('/api/docente/informes', [DocenteController::class, 'obtenerInformes'])->name('docente.obtener_informes');
    Route::get('/api/estudiantes/buscar', [DocenteController::class, 'buscarEstudiantes'])->name('estudiantes.buscar');

    /*
    |--------------------------------------------------------------------------
    | Rector (módulo adicional)
    |--------------------------------------------------------------------------
    */
    Route::prefix('rector')->name('rector.')->group(function () {
        // Solicitudes de beca
        Route::get('/becas', [RectorController::class, 'becasIndex'])->name('becas.index');
        Route::get('/becas/{id}/aprobar', [RectorController::class, 'aprobarBeca'])->name('becas.aprobar');
        Route::get('/becas/{id}/rechazar', [RectorController::class, 'rechazarBeca'])->name('becas.rechazar');

        // Información del colegio
        Route::get('/info', [RectorController::class, 'infoIndex'])->name('info.index');
        Route::get('/info/crear', [RectorController::class, 'createInfo'])->name('info.create');
        Route::post('/info', [RectorController::class, 'storeInfo'])->name('info.store');
        Route::get('/info/{id}/publicar', [RectorController::class, 'publicarInfo'])->name('info.publicar');

        // Plan anual
        Route::get('/plan', [RectorController::class, 'planIndex'])->name('plan.index');
        Route::get('/plan/{id}/aprobar', [RectorController::class, 'aprobarPlan'])->name('plan.aprobar');

        // Matrículas y gestión de personal
        Route::get('/matriculas', [RectorController::class, 'matriculasIndex'])->name('matriculas.index');
        Route::get('/docentes', [RectorController::class, 'docentesIndex'])->name('docentes.index');
    });

});

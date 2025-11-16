<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controladores
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\RolController;
use App\Http\Controllers\MatriculaAcudienteController;
use App\Http\Controllers\CoordinadorAcademicoController;
use App\Http\Controllers\TesoreroController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\CambiosNotasController;
use App\Http\Controllers\RecuperacionesController;
use App\Http\Controllers\ReportesAcademicosController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\InformacionColegioController;
use App\Http\Controllers\CitarAcudienteController;
use App\Http\Controllers\CasosDisciplinariosController;
use App\Http\Controllers\ReportesDisciplinariosController;
use App\Http\Controllers\AprobacionesNotasController;
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

    /*
    |--------------------------------------------------------------------------
    | Coordinador Académico
    |--------------------------------------------------------------------------
    */
    Route::prefix('coordinador-academico')->name('coordinador.')->group(function () {
        // Dashboard del coordinador académico
        Route::get('/', [CoordinadorAcademicoController::class, 'dashboard'])->name('dashboard');

        // Gestión de docentes
        Route::get('/gestion-docentes', [CoordinadorAcademicoController::class, 'gestionDocentes'])->name('gestion-docentes');

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
    // Página de creación de roles
    Route::get('/roles/create', function () {
        // Restringir acceso al Administrador del Sistema
        // if (!auth()->user()->role || auth()->user()->role->nombre !== 'AdministradorSistema') abort(403);
        return view('roles.create');
    })->name('roles.create');

    // Creación rápida de roles
    Route::post('/roles/quick-create', function (Request $request) {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'No autenticado.');
        }

        // Verificación de rol
        if (!$user->role || $user->role->nombre !== 'AdministradorSistema') {
            abort(403, 'Solo el Administrador del Sistema puede crear roles.');
        }

        // Validación de datos
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:roles,nombre'],
            'descripcion' => ['required', 'string', 'max:500'],
            'permisos' => ['nullable', 'string'], // CSV desde la vista
        ]);

        // Convertir CSV → Array
        $permisosArray = [];
        if (!empty($data['permisos'])) {
            $permisosArray = collect(explode(',', $data['permisos']))
                ->map(fn($p) => trim($p))
                ->filter()
                ->values()
                ->all();
        }

        RolesModel::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'permisos' => $permisosArray ?: null,
        ]);

        return redirect()->route('roles.create')->with('ok', 'Rol creado: ' . $data['nombre']);
    })->name('roles.quick-create');

    /*
    |--------------------------------------------------------------------------
    | Tesorería
    |--------------------------------------------------------------------------
    */
    Route::prefix('tesoreria')->name('tesoreria.')->group(function () {
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
    | Estudiantes / Acudientes
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
    /*
    |--------------------------------------------------------------------------
    | informacion
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

    /*
    |--------------------------------------------------------------------------  
    | Reportes disciplinarios
    |--------------------------------------------------------------------------  
    */
    Route::get('/reportes/gestion', [ReportesDisciplinariosController::class, 'gestion'])
    ->name('reportes.gestion');

    // Gestión de Docentes (coordinador Administrativo)
    Route::get('/gestiondocentes/gestion', [App\Http\Controllers\GestionDocentesController::class, 'gestion'])
    ->name('gestiondocentes.gestion');



});

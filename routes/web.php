<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\RolController;
use App\Http\Controllers\MatriculaAcudienteController;
use App\Models\RolesModel;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Crear usuarios
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Página de creación de rol (sin controlador)
    Route::get('/roles/create', function () {
        // Podrías restringir aquí también si prefieres que solo el admin vea la página:
        // if (!auth()->user()->role || auth()->user()->role->nombre !== 'AdministradorSistema') abort(403);
        return view('roles.create');
    })->name('roles.create');

    // Crear rol (sin controlador)
    Route::post('/roles/quick-create', function (Request $request) {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'No autenticado.');
        }

        // Tu seeder usa 'AdministradorSistema' (sin espacio)
        if (!$user->role || $user->role->nombre !== 'AdministradorSistema') {
            abort(403, 'Solo el Administrador del Sistema puede crear roles.');
        }

        $data = $request->validate([
            'nombre' => ['required','string','max:100','unique:roles,nombre'],
            'descripcion' => ['required','string','max:500'],
            'permisos' => ['nullable','string'], // CSV que enviamos desde la vista
        ]);

        // CSV → array
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

    // Rutas de tesorería / Tesorero
    Route::prefix('tesoreria')->name('tesoreria.')->group(function () {
<<<<<<< Updated upstream
        Route::get('/paz-y-salvo/{acudiente}', [\App\Http\Controllers\TesoreroController::class, 'generarPazYSalvo'])->name('pazysalvo');
        Route::post('/factura/matricula', [\App\Http\Controllers\TesoreroController::class, 'generarFacturaMatricula'])->name('factura.matricula');
        Route::post('/pago/registrar', [\App\Http\Controllers\TesoreroController::class, 'registrarPagoAcudiente'])->name('pago.registrar');
        Route::post('/devolucion', [\App\Http\Controllers\TesoreroController::class, 'gestionarDevolucion'])->name('devolucion');
        Route::get('/cartera', [\App\Http\Controllers\TesoreroController::class, 'gestionarCartera'])->name('cartera');
        Route::get('/reportes', [\App\Http\Controllers\TesoreroController::class, 'entregarReportes'])->name('reportes');
        Route::get('/estado-cuenta/{acudiente}', [\App\Http\Controllers\TesoreroController::class, 'consultarEstadoCuenta'])->name('estado.cuenta');
        Route::post('/beca', [\App\Http\Controllers\TesoreroController::class, 'registrarBecaDescuento'])->name('beca.registrar');
        Route::get('/reporte-financiero', [\App\Http\Controllers\TesoreroController::class, 'generarReporteFinanciero'])->name('reporte.financiero');
        Route::get('/info-colegio', [\App\Http\Controllers\TesoreroController::class, 'consultarInformacionColegio'])->name('info.colegio');
=======
        // Vistas (páginas) para tesorería
        Route::get('/paz-y-salvo', [TesoreroController::class, 'viewPazYSalvo'])->name('view.pazysalvo');
        Route::get('/factura/matricula', [TesoreroController::class, 'viewGenerarFactura'])->name('view.factura.matricula');
        Route::get('/pago/registrar', [TesoreroController::class, 'viewRegistrarPago'])->name('view.pago.registrar');
        Route::get('/devolucion', [TesoreroController::class, 'viewDevolucion'])->name('view.devolucion');
        Route::get('/cartera', [TesoreroController::class, 'viewCartera'])->name('view.cartera');
        Route::get('/reportes', [TesoreroController::class, 'viewReportes'])->name('view.reportes');
        Route::get('/estado-cuenta', [TesoreroController::class, 'viewEstadoCuenta'])->name('view.estado.cuenta');
        Route::get('/beca', [TesoreroController::class, 'viewBeca'])->name('view.beca');
        Route::get('/reporte-financiero', [TesoreroController::class, 'viewReporteFinanciero'])->name('view.reporte.financiero');
        Route::get('/info-colegio', [TesoreroController::class, 'viewInfoColegio'])->name('view.info.colegio');

        Route::get('/paz-y-salvo/{acudiente}', [TesoreroController::class, 'generarPazYSalvo'])->name('pazysalvo');
        Route::post('/factura/matricula', [TesoreroController::class, 'generarFacturaMatricula'])->name('factura.matricula');
        Route::post('/pago/registrar', [TesoreroController::class, 'registrarPagoAcudiente'])->name('pago.registrar');
        Route::post('/devolucion', [TesoreroController::class, 'gestionarDevolucion'])->name('devolucion');
        // API endpoints bajo /tesoreria/api/* para evitar conflictos con las vistas GET
        Route::prefix('api')->group(function () {
            Route::get('/cartera', [TesoreroController::class, 'gestionarCartera'])->name('api.cartera');
            Route::get('/reportes', [TesoreroController::class, 'entregarReportes'])->name('api.reportes');
            Route::get('/estado-cuenta/{acudiente}', [TesoreroController::class, 'consultarEstadoCuenta'])->name('api.estado.cuenta');
            Route::post('/beca', [TesoreroController::class, 'registrarBecaDescuento'])->name('api.beca.registrar');
            Route::get('/reporte-financiero', [TesoreroController::class, 'generarReporteFinanciero'])->name('api.reporte.financiero');
            Route::get('/info-colegio', [TesoreroController::class, 'consultarInformacionColegio'])->name('api.info.colegio');
        });
>>>>>>> Stashed changes
    });
});

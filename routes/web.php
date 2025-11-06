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
    });
});

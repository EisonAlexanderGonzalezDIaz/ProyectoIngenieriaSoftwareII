@extends('layouts.app')

@section('title', 'Panel Principal - Colegio')

@section('content')


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-graduate me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <div class="sidebar bg-light border-end vh-100">
                <div class="p-3 border-bottom">
                    <h6 class="text-primary text-uppercase fw-bold mb-0">Menú Principal</h6>
                </div>
                <nav class="nav flex-column px-3 mt-2">
                    <a class="nav-link active fw-semibold text-primary" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Rector', 'CoordinadorAcademico', 'CoordinadorDisciplinario', 'Orientador', 'Tesorero', 'Docente', 'AdministradorSistema']))
                    <a class="nav-link text-dark" href="{{ route('estudiantes.gestion') }}">
                        <i class="fas fa-user-graduate me-2"></i>Ver Estudiantes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['AdministradorSistema', 'Rector']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-building me-2"></i>Registrar informacion institucional
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['AdministradorSistema', 'Rector']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-user-shield me-2"></i>Asignar permisos y roles
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['AdministradorSistema', 'Rector']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-users-cog me-2"></i>Gestionar perfiles de usuario
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Rector', 'CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Gestionar Docentes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="{{ route('materias.gestion') }}">
                        <i class="fas fa-book-open me-2"></i>Gestionar Materias
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="{{ route('horarios.gestion') }}">
                        <i class="fas fa-calendar-alt me-2"></i>Gestionar Horarios
                    </a>
                    @endif
                     @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante', 'Acudiente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-calendar-plus me-2"></i>Solicitar citas orientacion
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-clock me-2"></i>Consultar horario
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-download me-2"></i>Descargar horario
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante', 'Acudiente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-clipboard-list me-2"></i>Consultar notas
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-book me-2"></i>Consultar materia
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-pen me-2"></i>Registrar notas
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-user-check me-2"></i>Consultar asistencia
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-upload me-2"></i>Subir material academico
                    </a>
                    @endif  
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-file-alt me-2"></i>Generar informes curso
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-check-circle me-2"></i>Calificar
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente', 'Estudiante', 'Acudiente', 'Rector']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-newspaper me-2"></i>Consultar boletines
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-envelope-open-text me-2"></i>Citar acudientes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante', 'Rector', 'CoordinadorAcademico', 'CoordinadorDisciplinario', 'Orientador', 'AdministradorSistema']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-graduation-cap me-2"></i>Consultar plan de estudio
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-download me-2"></i>Descargar material academico
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante', 'Acudiente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-certificate me-2"></i>Solicitar certificaciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-download me-2"></i>Descargar certificaciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante', 'Acudiente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-exclamation-circle me-2"></i>Consultar reportes disciplinarios
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Estudiante', 'Acudiente'. 'Docente', 'CoordinadorAcademico', 'CoordinadorDisciplinario', 'Orientador', 'Rector', 'Tesorero', 'AdministradorSistema']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-info-circle me-2"></i>Consultar información de colegio
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-check me-2"></i>Aprobar Cambios de Notas
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-undo-alt me-2"></i>Gestionar Recuperaciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico', 'CoordinadorDisciplinario', 'Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-envelope-open-text me-2"></i>Citar Acudientes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorAcademico']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-chart-line me-2"></i>Generar Reportes Academicos
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorDisciplinario']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-exclamation-triangle me-2"></i>Recibir casos disciplinarios
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorDisciplinario']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-flag me-2"></i>Reportar casos disciplinarios
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorDisciplinario']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-balance-scale me-2"></i>Revisar casos graves
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorDisciplinario']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-gavel me-2"></i>Asignar sanciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['CoordinadorDisciplinario', 'Docente']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-clipboard-list me-2"></i>Generar reportes disciplinarios
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Orientador']))
                    <a class="nav-link text-dark" href="#">
                         <i class="fas fa-book-reader me-2"></i>Gestionar Orientaciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Orientador']))
                    <a class="nav-link text-dark" href="#">
                         <i class="fas fa-exclamation-triangle me-2"></i>Revisar Casos Graves
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Orientador']))
                    <a class="nav-link text-dark" href="#">
                         <i class="fas fa-eye me-2"></i>Realizar Seguimiento
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Orientador']))
                    <a class="nav-link text-dark" href="#">
                         <i class="fas fa-user-clock me-2"></i>Atender Sesiones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-file-invoice me-2"></i>Generar paz y salvo
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-receipt me-2"></i>Generar recibos de matricula
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-credit-card me-2"></i>Registrar pagos estudiantes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-undo-alt me-2"></i>Gestionar devoluciones
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-wallet me-2"></i>Gestionar carteras
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-paper-plane me-2"></i>Enviar reportes
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-wallet me-2"></i>Consultar estado de cuenta
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Rector]))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-percent me-2"></i>Registrar becas y descuentos
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-chart-pie me-2"></i>Generar reportes financieros
                    </a>
                    @endif
                    @if(in_array(Auth::user()->rol->nombre ?? '', ['Tesorero']))
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-check-circle me-2"></i>Aprobar becas o descuentos
                    </a>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <!-- Welcome Section -->
                <div class="row mb-4 align-items-center">
                    <div class="col-12">
                        <h1 class="h3 text-primary fw-bold">¡Bienvenido(a), {{ Auth::user()->name }}!</h1>
                        <p class="text-muted">Administra y supervisa la gestión académica y administrativa del colegio.</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-primary mb-2"><i class="fas fa-user-graduate fa-2x"></i></div>
                                <h5 class="card-title">Estudiantes</h5>
                                <h3 class="text-primary">420</h3>
                                <small class="text-muted">Registrados en el sistema</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-success mb-2"><i class="fas fa-user-tie fa-2x"></i></div>
                                <h5 class="card-title">Docentes</h5>
                                <h3 class="text-success">35</h3>
                                <small class="text-muted">Activos actualmente</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-warning mb-2"><i class="fas fa-book fa-2x"></i></div>
                                <h5 class="card-title">Materias</h5>
                                <h3 class="text-warning">52</h3>
                                <small class="text-muted">Asignaturas registradas</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-info mb-2"><i class="fas fa-calendar-check fa-2x"></i></div>
                                <h5 class="card-title">Eventos</h5>
                                <h3 class="text-info">5</h3>
                                <small class="text-muted">Programados este mes</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0 text-primary">
                                    <i class="fas fa-rocket me-2"></i>Acciones Rápidas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="#" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-user-plus me-2"></i>Registrar Estudiante
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#" class="btn btn-outline-primary btn-lg w-100">
                                            <i class="fas fa-book me-2"></i>Agregar Materia
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#" class="btn btn-outline-success btn-lg w-100">
                                            <i class="fas fa-chart-pie me-2"></i>Ver Reportes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0 text-primary">
                                    <i class="fas fa-bell me-2"></i>Actividad Reciente
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center py-4">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hay actividades recientes para mostrar.</p>
                                    <p class="text-muted">¡Comienza registrando estudiantes o docentes!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection
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
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-user-tie me-2"></i>Docentes
                    </a>
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-user-graduate me-2"></i>Estudiantes
                    </a>
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-users me-2"></i>Acudientes
                    </a>
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-book-open me-2"></i>Materias
                    </a>
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-calendar-alt me-2"></i>Horarios
                    </a>
                    <a class="nav-link text-dark" href="#">
                        <i class="fas fa-chart-line me-2"></i>Reportes Académicos
                    </a>
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
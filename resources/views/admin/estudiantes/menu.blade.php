@extends('layouts.app')

@section('title', 'Consultar Estudiantes - Administrador del Sistema')

@section('content')
@php
    $usuario   = Auth::user();
    $rolNombre = $usuario->rol->nombre ?? '';
@endphp

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
                        <i class="fas fa-user-circle me-1"></i>{{ $usuario->name }}
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
                    {{-- Inicio --}}
                    <a class="nav-link text-dark" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>

                    {{-- Consultar Estudiantes (esta vista) --}}
                    <a class="nav-link active fw-semibold text-primary" href="{{ route('admin.estudiantes.menu') }}">
                        <i class="fas fa-user-graduate me-2"></i>Consultar Estudiantes
                    </a>

                    {{-- Asignar permisos y roles --}}
                    @if(in_array($rolNombre, ['AdministradorSistema', 'Rector']))
                        <a class="nav-link text-dark" href="{{ route('roles.create') }}">
                            <i class="fas fa-user-shield me-2"></i>Asignar permisos y roles
                        </a>
                    @endif

                    {{-- Gestionar perfiles de usuario (solo desde el menú lateral) --}}
                    @if(in_array($rolNombre, ['Rector']))
                        <a class="nav-link text-dark" href="{{ route('admin.usuarios.perfiles') }}">
                            <i class="fas fa-users-cog me-2"></i>Gestionar perfiles de usuario
                        </a>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 text-primary fw-bold">Consultar Estudiantes</h1>
                        <p class="text-muted">
                            Selecciona la opción para ver y gestionar los estudiantes del colegio.
                        </p>
                    </div>
                </div>

                <div class="row">
                    <!-- ÚNICA opción: ver estudiantes por curso -->
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <i class="fas fa-layer-group me-2 text-primary"></i>
                                    Estudiantes por curso
                                </h5>
                                <p class="text-muted">
                                    Consulta los estudiantes agrupados por curso y gestiona sus datos básicos.
                                </p>
                                <a href="{{ route('admin.estudiantes.porCurso') }}" class="btn btn-primary mt-auto">
                                    <i class="fas fa-search me-2"></i>Ir a estudiantes por curso
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Si luego quieres otra tarjeta diferente, se puede agregar aquí --}}
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection

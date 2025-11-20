@extends('layouts.app')

@section('title', 'Rector - Consultar notas')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRectorNotas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarRectorNotas">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2"></i>Ir al panel principal</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h1 class="h4 text-primary fw-bold mb-3">
        <i class="fas fa-clipboard-list me-2"></i>Consultar notas de estudiantes
    </h1>

    {{-- Filtros básicos --}}
    <form class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Nombre o documento del estudiante">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option value="">Curso...</option>
                <option>6°A</option>
                <option>6°B</option>
                <option>7°A</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option value="">Materia...</option>
                <option>Matemáticas</option>
                <option>Lengua Castellana</option>
                <option>Ciencias Naturales</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Buscar
            </button>
        </div>
    </form>

    {{-- Tabla de notas (ejemplo) --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Periodo</th>
                        <th>Definitiva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Juan Pérez</td>
                        <td>6°A</td>
                        <td>Matemáticas</td>
                        <td>2025 - P1</td>
                        <td>4.2</td>
                    </tr>
                    <tr>
                        <td>María López</td>
                        <td>7°B</td>
                        <td>Lengua Castellana</td>
                        <td>2025 - P1</td>
                        <td>4.8</td>
                    </tr>
                    <tr>
                        <td>Carlos Rodríguez</td>
                        <td>8°A</td>
                        <td>Ciencias Naturales</td>
                        <td>2025 - P2</td>
                        <td>3.9</td>
                    </tr>
                </tbody>
            </table>
            <small class="text-muted">
                * Tabla de demostración. Puedes conectar aquí tus modelos de notas o un endpoint API.
            </small>
        </div>
    </div>
</div>
@endsection

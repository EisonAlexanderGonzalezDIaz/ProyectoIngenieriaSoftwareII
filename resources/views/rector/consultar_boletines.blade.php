@extends('layouts.app')

@section('title', 'Rector - Consultar boletines')

@section('content')
{{-- Navbar Colegio San Martín --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRectorBoletines">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarRectorBoletines">
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
        <i class="fas fa-newspaper me-2"></i>Consultar boletines de estudiantes
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
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Buscar
            </button>
        </div>
    </form>

    {{-- Tabla de resultados (dummy, lista básica pero funcional) --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ejemplos estáticos. Luego puedes reemplazar con datos reales desde un controlador --}}
                    <tr>
                        <td>Juan Pérez</td>
                        <td>6°A</td>
                        <td>2025 - Periodo 1</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i>Ver / descargar boletín
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>María López</td>
                        <td>7°B</td>
                        <td>2025 - Periodo 1</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i>Ver / descargar boletín
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Carlos Rodríguez</td>
                        <td>8°A</td>
                        <td>2025 - Periodo 2</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i>Ver / descargar boletín
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <small class="text-muted">
                * Estos datos son de ejemplo. Luego puedes conectarlos a tus modelos de boletines.
            </small>
        </div>
    </div>
</div>
@endsection

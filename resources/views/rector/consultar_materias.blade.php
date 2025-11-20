@extends('layouts.app')

@section('title', 'Rector - Consultar materias')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRectorMaterias">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarRectorMaterias">
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
        <i class="fas fa-book me-2"></i>Consultar materias por curso
    </h1>

    {{-- Filtros --}}
    <form class="row g-2 mb-3">
        <div class="col-md-4">
            <select class="form-select">
                <option value="">Seleccione un curso...</option>
                <option>6°A</option>
                <option>7°B</option>
                <option>8°A</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Ver materias
            </button>
        </div>
    </form>

    {{-- Lista de materias (ejemplo) --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Intensidad horaria</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>6°A</td>
                        <td>Matemáticas</td>
                        <td>Pedro Gómez</td>
                        <td>4 h/semana</td>
                    </tr>
                    <tr>
                        <td>6°A</td>
                        <td>Ciencias Naturales</td>
                        <td>Lucía Ramírez</td>
                        <td>3 h/semana</td>
                    </tr>
                    <tr>
                        <td>6°A</td>
                        <td>Inglés</td>
                        <td>Ana Torres</td>
                        <td>3 h/semana</td>
                    </tr>
                </tbody>
            </table>
            <small class="text-muted">
                * Ejemplo de estructura para materias por curso. Luego puedes poblarla desde tu base de datos de materias/horarios.
            </small>
        </div>
    </div>
</div>
@endsection

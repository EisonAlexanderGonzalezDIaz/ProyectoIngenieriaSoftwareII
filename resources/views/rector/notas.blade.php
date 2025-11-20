<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rector - Consultar notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">
@php
    $usuario = Auth::user();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>
        <span class="navbar-text text-white ms-auto">
            <i class="fas fa-user-circle me-1"></i>{{ $usuario->name ?? 'Rector' }}
        </span>
    </div>
</nav>

<div class="container mb-5">
    <h1 class="h4 mb-3 text-primary">
        <i class="fas fa-clipboard-list me-2"></i>Consultar notas de estudiantes
    </h1>

    {{-- Mismo filtro que en boletines --}}
    <form method="GET" action="{{ route('rector.notas') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Curso</label>
            <select name="curso_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Seleccione un curso --</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}"
                        {{ optional($cursoSeleccionado)->id == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($cursoSeleccionado)
            <div class="col-md-4">
                <label class="form-label">Estudiante</label>
                <select name="estudiante_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccione un estudiante --</option>
                    @foreach($estudiantes as $est)
                        <option value="{{ $est->id }}"
                            {{ optional($estudianteSeleccionado)->id == $est->id ? 'selected' : '' }}>
                            {{ $est->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </form>

    @if($estudianteSeleccionado)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <strong>Notas de: {{ $estudianteSeleccionado->name }}</strong>
            </div>
            <div class="card-body">
                {{-- Aquí luego conectas con tu tabla de notas --}}
                <p class="text-muted">
                    Aquí se mostrarían las notas del estudiante organizadas por materia y periodo.
                </p>
            </div>
        </div>
    @else
        <p class="text-muted">
            Selecciona un curso y un estudiante para ver sus notas.
        </p>
    @endif
</div>

</body>
</html>

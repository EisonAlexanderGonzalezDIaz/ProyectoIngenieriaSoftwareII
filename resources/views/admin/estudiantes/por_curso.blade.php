@extends('layouts.app')

@section('title', 'Estudiantes por curso')

@section('content')
@include('partials.navbar')

<div class="container py-4">

    <h1 class="h4 text-primary mb-3">Estudiantes por curso</h1>

    {{-- Selector de curso --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.estudiantes.porCurso') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label mb-1">Curso</label>
                    <select name="curso_id" class="form-select" required>
                        <option value="">Seleccione un curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}"
                                {{ optional($cursoSeleccionado)->id == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 mt-3 mt-md-0">
                        Ver estudiantes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Si ya se seleccionó un curso, mostramos la tabla --}}
    @if($cursoSeleccionado)
        <h2 class="h5 mb-3">Curso: {{ $cursoSeleccionado->nombre }}</h2>

        <div class="card">
            <div class="card-body">
                @if($estudiantes->isEmpty())
                    <p class="text-muted mb-0">
                        No hay estudiantes registrados en este curso.
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudiantes as $index => $estudiante)
                                    <tr>
                                        {{-- Número consecutivo dentro del curso --}}
                                        <td>{{ $index + 1 }}</td>

                                        {{-- 🔹 OJO: ahora $estudiante ES User, así que usamos ->name directamente --}}
                                        <td>{{ $estudiante->name ?? 'N/A' }}</td>

                                        {{-- Documento: si tienes columna "documento" en users, se mostrará;
                                             si no, quedará N/A y no rompe nada --}}
                                        <td>{{ $estudiante->documento ?? 'N/A' }}</td>

                                        <td>{{ $estudiante->email ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection

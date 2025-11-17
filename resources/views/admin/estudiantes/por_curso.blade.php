@extends('layouts.app')

@section('title', 'Estudiantes por curso')

@section('content')
@include('partials.navbar')

<div class="container py-4">
    <h1 class="h4 text-primary mb-4">Estudiantes por curso</h1>

    {{-- Filtro por curso --}}
    <form method="GET" action="{{ route('admin.estudiantes.porCurso') }}" class="row g-2 mb-4">
        <div class="col-md-8">
            <select name="curso_id" class="form-select">
    <option value="">Seleccione un curso</option>
    @foreach($cursos as $curso)
        <option value="{{ $curso->id }}"
            {{ optional($cursoSeleccionado)->id == $curso->id ? 'selected' : '' }}>
            {{ $curso->nombre }}
        </option>
    @endforeach
</select>

        </div>
        <div class="col-md-4">
            <button class="btn btn-primary w-100" type="submit">
                Ver estudiantes
            </button>
        </div>
    </form>

    {{-- Tabla --}}
    @if($cursoSeleccionado)
        <h2 class="h5 mb-3">
            Curso: {{ $cursoSeleccionado->nombre ?? 'Curso '.$cursoSeleccionado->id }}
        </h2>

        @if($estudiantes->isEmpty())
            <p>No hay estudiantes registrados en este curso.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $index => $est)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $est->nombre ?? ($est->nombres.' '.$est->apellidos) }}</td>
                                <td>{{ $est->documento ?? 'N/A' }}</td>
                                <td>{{ $est->email ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <p>Selecciona un curso y haz clic en "Ver estudiantes".</p>
    @endif
</div>
@endsection

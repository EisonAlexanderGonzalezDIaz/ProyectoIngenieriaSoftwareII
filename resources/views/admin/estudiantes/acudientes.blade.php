@extends('layouts.app')

@section('title', 'Gestionar acudientes')

@section('content')
@include('partials.navbar')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1 text-primary">
                Acudientes de: {{ $estudiante->name }}
            </h1>
            <div class="text-muted small">
                Rol: {{ optional($estudiante->rol)->nombre ?? 'Sin rol' }} ·
                Curso: {{ optional($estudiante->curso)->nombre ?? 'Sin curso asignado' }} ·
                Email: {{ $estudiante->email }}
            </div>
        </div>

        <a href="{{ route('admin.usuarios.perfiles') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a perfiles
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Acudientes actuales --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Acudientes vinculados actualmente</strong>
        </div>
        <div class="card-body p-0">
            @if($estudiante->acudientes->isEmpty())
                <p class="p-3 mb-0 text-muted">
                    Este estudiante aún no tiene acudientes asignados.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiante->acudientes as $idx => $acu)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $acu->name }}</td>
                                    <td>{{ $acu->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Formulario para asignar acudientes --}}
    <form method="POST"
          action="{{ route('admin.usuarios.acudientes.update', $estudiante->id) }}">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <strong>Asignar / actualizar acudientes</strong>
                <span class="text-muted d-block small">
                    Marca los acudientes que estarán vinculados a este estudiante.
                </span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($acudientesDisponibles as $acu)
                        <label class="list-group-item d-flex align-items-center">
                            <input
                                type="checkbox"
                                name="acudientes[]"
                                value="{{ $acu->id }}"
                                class="form-check-input me-2"
                                @checked($estudiante->acudientes->contains('id', $acu->id))
                            >
                            <div>
                                <div>{{ $acu->name }}</div>
                                <small class="text-muted">{{ $acu->email }}</small>
                            </div>
                        </label>
                    @empty
                        <div class="p-3 text-muted">
                            No hay usuarios con rol "Acudiente" registrados.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Guardar acudientes
            </button>
            <a href="{{ route('admin.usuarios.perfiles') }}" class="btn btn-outline-secondary">
                Volver a perfiles
            </a>
        </div>
    </form>
</div>
@endsection

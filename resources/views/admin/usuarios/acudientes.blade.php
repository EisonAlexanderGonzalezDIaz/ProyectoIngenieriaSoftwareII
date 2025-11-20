@extends('layouts.app')

@section('title', 'Gestionar acudientes')

@section('content')
@include('partials.navbar')

<div class="container py-4">
    <h1 class="h4 mb-3 text-primary">
        Gestionar acudientes de: {{ $user->name }}
    </h1>

    <p>
        <strong>Rol:</strong> {{ optional($user->rol)->nombre ?? 'Sin rol' }}<br>
        <strong>Curso:</strong> {{ optional($user->curso)->nombre ?? 'Sin curso asignado' }}
    </p>

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

    <form method="POST" action="{{ route('admin.usuarios.acudientes.store', $user->id) }}">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <strong>Acudientes disponibles</strong>
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
                                @checked(in_array($acu->id, $acudientesSeleccionados))
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

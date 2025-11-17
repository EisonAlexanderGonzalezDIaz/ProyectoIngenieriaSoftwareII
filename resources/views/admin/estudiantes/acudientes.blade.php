@extends('layouts.app')

@section('title', 'Acudientes del estudiante')

@section('content')
@include('partials.navbar')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-primary mb-0">
            Acudientes de {{ $estudiante->name }}
        </h1>

        <a href="{{ route('admin.usuarios.perfiles') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a gestión de perfiles
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('ok'))
        <div class="alert alert-success">
            {{ session('ok') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario para agregar acudiente --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Agregar acudiente por correo electrónico</strong>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.estudiantes.acudientes.vincular', $estudiante->id) }}"
                  class="row g-2 align-items-end">
                @csrf

                <div class="col-md-6">
                    <label class="form-label mb-1">Correo del acudiente</label>
                    <input type="email"
                           name="email_acudiente"
                           class="form-control"
                           value="{{ old('email_acudiente') }}"
                           placeholder="acudiente@correo.com"
                           required>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus me-1"></i> Vincular
                    </button>
                </div>
            </form>

            <p class="text-muted small mt-2 mb-0">
                El usuario debe existir y tener rol <strong>Acudiente</strong>.
            </p>
        </div>
    </div>

    {{-- Tabla de acudientes vinculados --}}
    <div class="card">
        <div class="card-header">
            <strong>Acudientes vinculados</strong>
        </div>
        <div class="card-body">
            @if($acudientes->isEmpty())
                <p class="text-muted mb-0">
                    Este estudiante aún no tiene acudientes vinculados.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-center" style="width: 140px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($acudientes as $acudiente)
                                <tr>
                                    <td>{{ $acudiente->name }}</td>
                                    <td>{{ $acudiente->email }}</td>
                                    <td>{{ optional($acudiente->rol)->nombre ?? 'Acudiente' }}</td>
                                    <td class="text-center">
                                        <form method="POST"
                                              action="{{ route('admin.estudiantes.acudientes.desvincular', [$estudiante->id, $acudiente->id]) }}"
                                              onsubmit="return confirm('¿Quitar este acudiente del estudiante?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-user-minus me-1"></i> Quitar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

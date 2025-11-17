@extends('layouts.app')

@section('title', 'Gestionar perfiles de usuario')

@section('content')
@include('partials.navbar')

<div class="container py-4">

    {{-- Encabezado con botón de inicio --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-primary mb-0">Gestionar perfiles de usuario</h1>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-home me-1"></i> Volver al inicio
        </a>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('ok'))
        <div class="alert alert-success">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🔍 Filtros de búsqueda --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.usuarios.perfiles') }}" class="row g-2 align-items-end">
                {{-- Buscar --}}
                <div class="col-md-4">
                    <label class="form-label mb-1">Buscar</label>
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           class="form-control"
                           placeholder="Nombre o email">
                </div>

                {{-- Filtro por rol --}}
                <div class="col-md-3">
                    <label class="form-label mb-1">Rol</label>
                    <select name="rol_id" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}"
                                {{ (string)request('rol_id') === (string)$rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por estado --}}
                <div class="col-md-3">
                    <label class="form-label mb-1">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                {{-- Botón filtrar --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🧾 Tabla de usuarios --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="min-width: 160px;">Nombre</th>
                            <th style="min-width: 200px;">Email</th>
                            <th style="min-width: 150px;">Curso</th>
                            <th style="min-width: 180px;">Rol</th>
                            <th style="width: 140px;">Estado</th>
                            <th style="width: 160px;" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $index => $user)
                            <form method="POST"
                                  action="{{ route('admin.usuarios.perfil.update', $user->id) }}">
                                @csrf
                                @method('PUT')

                                <tr>
                                    {{-- Número de fila con paginación correcta --}}
                                    <td>{{ $user->id }}</td> 

                                    <td>{{ $user->name }}</td>

                                    <td>{{ $user->email }}</td>

                                    {{-- Curso (solo para Estudiantes) --}}
                                    <td>
                                        @if(optional($user->rol)->nombre === 'Estudiante')
                                            <select name="curso_id" class="form-select form-select-sm">
                                                <option value="">Sin curso</option>
                                                @foreach($cursos as $curso)
                                                    <option value="{{ $curso->id }}"
                                                        {{ $user->curso_id == $curso->id ? 'selected' : '' }}>
                                                        {{ $curso->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-muted small">No aplica</span>
                                        @endif
                                    </td>

                                    {{-- Rol (select editable) --}}
                                    <td>
                                        <select name="rol_id" class="form-select form-select-sm">
                                            @foreach($roles as $rol)
                                                <option value="{{ $rol->id }}"
                                                    {{ optional($user->rol)->id == $rol->id ? 'selected' : '' }}>
                                                    {{ $rol->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Estado (checkbox + badge) --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check mb-0">
                                                <input type="hidden" name="activo" value="0">
                                                <input class="form-check-input" type="checkbox" name="activo" value="1"
                                                    id="activo_{{ $user->id }}"
                                                    {{ ($user->activo ?? 1) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="activo_{{ $user->id }}">
                                                    Activo
                                                </label>
                                            </div>
                                            @if(($user->activo ?? 1))
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Botones de acción --}}
                                        <td class="text-center">
                                        <div class="d-flex flex-column gap-1">
                                        {{-- Guardar rol/estado/curso --}}
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                        Guardar
                                        </button>
                                        {{-- Abrir modal para editar datos básicos --}}
                                        <button type="button"
                                        class="btn btn-sm btn-outline-secondary w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal_{{ $user->id }}">
                                        <i class="fas fa-pen me-1"></i> Editar datos
                                        </button>
                                        {{-- Gestionar acudientes (solo estudiantes) --}}
                                        @if(optional($user->rol)->nombre === 'Estudiante')
                                        <a href="{{ route('admin.estudiantes.acudientes.index', $user->id) }}"
                                        class="btn btn-sm btn-outline-info w-100">
                                        <i class="fas fa-users me-1"></i> Acudientes
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </td>
                    </tr>
                </form>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    No se encontraron usuarios con los filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📄 Paginación --}}
            <div class="mt-3">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>

    {{-- Modales para editar datos básicos --}}
    @foreach($usuarios as $user)
        <div class="modal fade" id="editUserModal_{{ $user->id }}" tabindex="-1"
             aria-labelledby="editUserModalLabel_{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.usuarios.basicos.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel_{{ $user->id }}">
                                Editar datos de {{ $user->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ $user->name }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ $user->email }}"
                                       required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                Guardar cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection

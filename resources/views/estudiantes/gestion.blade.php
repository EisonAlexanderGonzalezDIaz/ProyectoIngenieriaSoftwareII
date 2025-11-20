@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user-graduate"></i> Gestión de Estudiantes</h2>
            <p class="text-muted">Listado de estudiantes registrados en el sistema.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0"><i class="fas fa-list"></i> Estudiantes</h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Estudiante
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Curso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableEstudiantes">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Cargar estudiantes al iniciar (placeholder)
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('tableEstudiantes');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Sin estudiantes registrados aún.</td></tr>';
    });
</script>
@endsection

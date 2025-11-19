@extends('layouts.app')

@section('title', 'Aprobar Becas y Descuentos')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">Aprobar Becas y Descuentos</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Aquí podrás revisar las solicitudes de becas y descuentos pendientes y aprobarlas o rechazarlas.</p>

                    <!-- Placeholder table: will be wired to API later -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Acudiente</th>
                                    <th>Monto</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="becas-list">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay solicitudes disponibles (implementación pendiente).</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

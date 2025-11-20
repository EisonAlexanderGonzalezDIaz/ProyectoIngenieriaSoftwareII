@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-graduation-cap"></i> Gestión de Solicitudes de Beca</h2>
        </div>
    </div>

    <!-- Tabs de filtrado por estado -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#" onclick="cargarSolicitudes('solicitado')" role="tab">
                <i class="fas fa-clock"></i> Solicitadas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="cargarSolicitudes('en_revision')" role="tab">
                <i class="fas fa-hourglass-half"></i> En Revisión
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="cargarSolicitudes('aprobado')" role="tab">
                <i class="fas fa-check-circle"></i> Aprobadas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="cargarSolicitudes('rechazado')" role="tab">
                <i class="fas fa-times-circle"></i> Rechazadas
            </a>
        </li>
    </ul>

    <!-- Tabla de solicitudes -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tablaSolicitudes">
                <thead class="table-light">
                    <tr>
                        <th>Acudiente</th>
                        <th>Estudiante</th>
                        <th>Tipo de Beca</th>
                        <th>Monto Solicitado</th>
                        <th>Detalle</th>
                        <th>Fecha Solicitud</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTabla">
                    <tr>
                        <td colspan="8" class="text-center py-3">Cargando solicitudes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <nav aria-label="Page navigation" class="mt-3">
        <ul class="pagination" id="paginacion"></ul>
    </nav>
</div>

<!-- Modal para aprobar solicitud -->
<div class="modal fade" id="modalAprobar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Aprobar Solicitud</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAprobar">
                    <div class="mb-3">
                        <label for="motivoAprobar" class="form-label">Motivo (opcional)</label>
                        <textarea class="form-control" id="motivoAprobar" rows="3" placeholder="Ej: Excelente desempeño académico"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAprobar()" id="btnAprobar">
                    <i class="fas fa-check"></i> Aprobar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para rechazar solicitud -->
<div class="modal fade" id="modalRechazar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rechazar Solicitud</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRechazar">
                    <div class="mb-3">
                        <label for="motivoRechazar" class="form-label">Motivo (requerido)</label>
                        <textarea class="form-control" id="motivoRechazar" rows="3" required placeholder="Explique por qué se rechaza la solicitud"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarRechazar()" id="btnRechazar">
                    <i class="fas fa-times"></i> Rechazar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-solicitado {
        background-color: #ffc107;
        color: #000;
    }
    .badge-en_revision {
        background-color: #17a2b8;
    }
    .badge-aprobado {
        background-color: #28a745;
    }
    .badge-rechazado {
        background-color: #dc3545;
    }
</style>

<script>
    let estadoActual = 'solicitado';
    let solicitudActualId = null;

    function cargarSolicitudes(estado) {
        estadoActual = estado;
        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
        event.target.closest('.nav-link').classList.add('active');

        const cuerpo = document.getElementById('cuerpoTabla');
        cuerpo.innerHTML = '<tr><td colspan="8" class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>';

        fetch(`{{ route('tesoreria.api.solicitudes.beca') }}?estado=${estado}`)
            .then(res => res.json())
            .then(data => {
                if (data.solicitudes.data.length === 0) {
                    cuerpo.innerHTML = '<tr><td colspan="8" class="text-center py-3">No hay solicitudes</td></tr>';
                    return;
                }

                cuerpo.innerHTML = data.solicitudes.data.map(sol => `
                    <tr>
                        <td><strong>${sol.acudiente?.name || 'N/A'}</strong></td>
                        <td>${sol.estudiante?.name || 'No asignado'}</td>
                        <td>${sol.tipo}</td>
                        <td>$${parseFloat(sol.monto_estimado).toLocaleString('es-CO')}</td>
                        <td><small>${(sol.detalle || 'Sin detalles').substring(0, 50)}...</small></td>
                        <td>${new Date(sol.fecha_solicitud).toLocaleDateString('es-CO')}</td>
                        <td><span class="badge badge-${sol.estado}">${sol.estado}</span></td>
                        <td>
                            ${sol.estado === 'solicitado' ? `
                                <button class="btn btn-sm btn-outline-info" onclick="marcarEnRevision(${sol.id})" title="Marcar en revisión">
                                    <i class="fas fa-hourglass-half"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="mostrarModalAprobar(${sol.id})" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="mostrarModalRechazar(${sol.id})" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : (sol.estado === 'en_revision' ? `
                                <button class="btn btn-sm btn-outline-success" onclick="mostrarModalAprobar(${sol.id})" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="mostrarModalRechazar(${sol.id})" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : '<span class="text-muted"><i class="fas fa-lock"></i> Resuelta</span>')}
                        </td>
                    </tr>
                `).join('');

                // Actualizar paginación
                actualizarPaginacion(data.solicitudes);
            })
            .catch(err => {
                console.error(err);
                cuerpo.innerHTML = '<tr><td colspan="8" class="text-center py-3 text-danger">Error al cargar solicitudes</td></tr>';
            });
    }

    function actualizarPaginacion(paginator) {
        const pag = document.getElementById('paginacion');
        pag.innerHTML = '';

        if (paginator.last_page > 1) {
            if (paginator.current_page > 1) {
                pag.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${paginator.current_page - 1})">Anterior</a></li>`;
            }
            for (let i = 1; i <= paginator.last_page; i++) {
                pag.innerHTML += `<li class="page-item ${i === paginator.current_page ? 'active' : ''}"><a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a></li>`;
            }
            if (paginator.current_page < paginator.last_page) {
                pag.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${paginator.current_page + 1})">Siguiente</a></li>`;
            }
        }
    }

    function cambiarPagina(pagina) {
        // Implementar si necesita paginación real
    }

    function marcarEnRevision(id) {
        fetch(`{{ route('tesoreria.solicitud.en_revision', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Marcada en revisión');
                cargarSolicitudes(estadoActual);
            }
        })
        .catch(err => alert('Error: ' + err));
    }

    function mostrarModalAprobar(id) {
        solicitudActualId = id;
        document.getElementById('motivoAprobar').value = '';
        new bootstrap.Modal(document.getElementById('modalAprobar')).show();
    }

    function mostrarModalRechazar(id) {
        solicitudActualId = id;
        document.getElementById('motivoRechazar').value = '';
        new bootstrap.Modal(document.getElementById('modalRechazar')).show();
    }

    function confirmarAprobar() {
        const motivo = document.getElementById('motivoAprobar').value;
        fetch(`{{ route('tesoreria.solicitud.aprobar', ':id') }}`.replace(':id', solicitudActualId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ motivo })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Solicitud aprobada');
                bootstrap.Modal.getInstance(document.getElementById('modalAprobar')).hide();
                cargarSolicitudes(estadoActual);
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(err => alert('Error: ' + err));
    }

    function confirmarRechazar() {
        const motivo = document.getElementById('motivoRechazar').value;
        if (!motivo.trim()) {
            alert('Debe ingresar un motivo');
            return;
        }
        fetch(`{{ route('tesoreria.solicitud.rechazar', ':id') }}`.replace(':id', solicitudActualId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ motivo })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Solicitud rechazada');
                bootstrap.Modal.getInstance(document.getElementById('modalRechazar')).hide();
                cargarSolicitudes(estadoActual);
            } else {
                alert('Error: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(err => alert('Error: ' + err));
    }

    // Cargar solicitudes al iniciar
    document.addEventListener('DOMContentLoaded', () => {
        cargarSolicitudes('solicitado');
    });
</script>
@endsection

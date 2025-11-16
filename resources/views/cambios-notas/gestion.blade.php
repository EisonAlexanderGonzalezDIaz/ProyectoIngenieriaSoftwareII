{{-- resources/views/cambios-notas/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .page-header {
        background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
    }

    .stat-card {
        border: 0;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    }

    .stat-title {
        font-size: .95rem;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13,110,253,0.04);
    }

    .badge-pendiente {
        background-color: #0dcaf0;
        color: #212529;
    }

    .badge-aprobado {
        background-color: #198754;
        color: #fff;
    }

    .badge-rechazado {
        background-color: #dc3545;
        color: #fff;
    }
</style>

<div class="container py-4">

    {{-- =========================
         GESTIÓN DE CAMBIOS DE NOTAS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header page-header rounded-top d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-edit me-2"></i>Aprobar Cambios de Notas
            </h4>
            <div>
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i> Panel de inicio
                </a>
                <button class="btn btn-light btn-sm me-2" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="exportCambios()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Estadísticas --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title stat-title">
                                <i class="fas fa-hourglass-half me-2"></i>Pendientes
                            </h5>
                            <h3 class="mb-0">5</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title stat-title">
                                <i class="fas fa-check me-2"></i>Aprobados
                            </h5>
                            <h3 class="mb-0">12</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-danger text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title stat-title">
                                <i class="fas fa-times me-2"></i>Rechazados
                            </h5>
                            <h3 class="mb-0">3</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-secondary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title stat-title">
                                <i class="fas fa-list me-2"></i>Total
                            </h5>
                            <h3 class="mb-0">20</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input
                    type="text"
                    id="searchCambios"
                    class="form-control"
                    placeholder="Buscar por estudiante, materia o docente...">
            </div>

            {{-- Filtros --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterEstado">
                        <option value="">Filtrar por Estado...</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterGrado">
                        <option value="">Filtrar por Grado...</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                        <option value="11">11°</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" id="filterFecha" class="form-control form-control-sm">
                </div>
            </div>

            {{-- Tabla de cambios de notas --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Materia</th>
                            <th>Nota Anterior</th>
                            <th>Nota Nueva</th>
                            <th>Docente Solicitante</th>
                            <th>Razón</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CAMBIO001</td>
                            <td>Juan Pérez</td>
                            <td>Matemáticas</td>
                            <td>3.2</td>
                            <td>3.8</td>
                            <td>Prof. García</td>
                            <td>Corrección de calificación</td>
                            <td>2025-11-10</td>
                            <td><span class="badge badge-pendiente">Pendiente</span></td>
                            <td>
                                <button class="btn btn-outline-success btn-sm me-1" onclick="abrirModalAprobar('CAMBIO001')" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm me-1" onclick="abrirModalRechazar('CAMBIO001')" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetalles('CAMBIO001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAMBIO002</td>
                            <td>María González</td>
                            <td>Español</td>
                            <td>2.8</td>
                            <td>3.5</td>
                            <td>Prof. López</td>
                            <td>Recalificación por revisión</td>
                            <td>2025-11-09</td>
                            <td><span class="badge badge-pendiente">Pendiente</span></td>
                            <td>
                                <button class="btn btn-outline-success btn-sm me-1" onclick="abrirModalAprobar('CAMBIO002')" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm me-1" onclick="abrirModalRechazar('CAMBIO002')" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetalles('CAMBIO002')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAMBIO003</td>
                            <td>Carlos López</td>
                            <td>Inglés</td>
                            <td>4.0</td>
                            <td>4.5</td>
                            <td>Prof. Martínez</td>
                            <td>Mejora académica</td>
                            <td>2025-11-08</td>
                            <td><span class="badge badge-aprobado">Aprobado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetalles('CAMBIO003')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAMBIO004</td>
                            <td>Ana Rodríguez</td>
                            <td>Ciencias</td>
                            <td>3.5</td>
                            <td>3.9</td>
                            <td>Prof. Rodríguez</td>
                            <td>Corrección de error administrativo</td>
                            <td>2025-11-07</td>
                            <td><span class="badge badge-pendiente">Pendiente</span></td>
                            <td>
                                <button class="btn btn-outline-success btn-sm me-1" onclick="abrirModalAprobar('CAMBIO004')" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm me-1" onclick="abrirModalRechazar('CAMBIO004')" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetalles('CAMBIO004')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAMBIO005</td>
                            <td>Roberto Torres</td>
                            <td>Sociales</td>
                            <td>2.5</td>
                            <td>2.8</td>
                            <td>Prof. Pérez</td>
                            <td>Revisión de evaluación</td>
                            <td>2025-11-06</td>
                            <td><span class="badge badge-rechazado">Rechazado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetalles('CAMBIO005')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <nav aria-label="Paginación cambios">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

{{-- =====================================
        MODAL PARA APROBAR CAMBIO
===================================== --}}
<div class="modal fade" id="modalAprobar" tabindex="-1" aria-labelledby="modalAprobarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAprobarLabel">
                    <i class="fas fa-check me-2"></i>Aprobar Cambio de Nota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAprobar">
                    <div class="mb-3">
                        <label class="form-label">Razón de Aprobación <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="razonAprobar" rows="3" placeholder="Explica por qué apruebas este cambio..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario (Opcional)</label>
                        <textarea class="form-control" id="comentarioAprobar" rows="2" placeholder="Comentarios adicionales..."></textarea>
                    </div>
                    <input type="hidden" id="idCambioAprobar">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAprobar()">
                    <i class="fas fa-check me-2"></i>Aprobar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        MODAL PARA RECHAZAR CAMBIO
===================================== --}}
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-labelledby="modalRechazarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRechazarLabel">
                    <i class="fas fa-times me-2"></i>Rechazar Cambio de Nota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRechazar">
                    <div class="mb-3">
                        <label class="form-label">Razón de Rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="razonRechazar" rows="3" placeholder="Explica por qué rechazas este cambio..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario (Opcional)</label>
                        <textarea class="form-control" id="comentarioRechazar" rows="2" placeholder="Comentarios adicionales..."></textarea>
                    </div>
                    <input type="hidden" id="idCambioRechazar">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarRechazar()">
                    <i class="fas fa-times me-2"></i>Rechazar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        MODAL PARA VER DETALLES
===================================== --}}
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetallesLabel">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Cambio de Nota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Cambio:</strong> <span id="detalleCambioId"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Estado:</strong> <span id="detalleEstado"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Estudiante:</strong> <span id="detalleEstudiante"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Materia:</strong> <span id="detalleMateria"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nota Anterior:</strong> <span id="detalleNotaAnterior"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Nota Nueva:</strong> <span id="detalleNotaNueva"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Docente Solicitante:</strong> <span id="detalleDocente"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Razón del Cambio:</strong>
                        <p id="detalleRazon" class="bg-light p-2 rounded"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Fecha de Solicitud:</strong> <span id="detalleFecha"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    // Abrir modal para aprobar
    function abrirModalAprobar(id) {
        document.getElementById('idCambioAprobar').value = id;
        document.getElementById('razonAprobar').value = '';
        document.getElementById('comentarioAprobar').value = '';
        new bootstrap.Modal(document.getElementById('modalAprobar')).show();
    }

    // Abrir modal para rechazar
    function abrirModalRechazar(id) {
        document.getElementById('idCambioRechazar').value = id;
        document.getElementById('razonRechazar').value = '';
        document.getElementById('comentarioRechazar').value = '';
        new bootstrap.Modal(document.getElementById('modalRechazar')).show();
    }

    // Confirmar aprobación
    function confirmarAprobar() {
        const id = document.getElementById('idCambioAprobar').value;
        const razon = document.getElementById('razonAprobar').value;
        const comentario = document.getElementById('comentarioAprobar').value;

        if (!razon.trim()) {
            alert('Por favor, ingresa una razón para la aprobación.');
            return;
        }

        alert('✅ Cambio de nota aprobado: ' + id + '\nRazón: ' + razon);
        bootstrap.Modal.getInstance(document.getElementById('modalAprobar')).hide();
        // Aquí iría la llamada AJAX al servidor
    }

    // Confirmar rechazo
    function confirmarRechazar() {
        const id = document.getElementById('idCambioRechazar').value;
        const razon = document.getElementById('razonRechazar').value;
        const comentario = document.getElementById('comentarioRechazar').value;

        if (!razon.trim()) {
            alert('Por favor, ingresa una razón para el rechazo.');
            return;
        }

        alert('❌ Cambio de nota rechazado: ' + id + '\nRazón: ' + razon);
        bootstrap.Modal.getInstance(document.getElementById('modalRechazar')).hide();
        // Aquí iría la llamada AJAX al servidor
    }

    // Ver detalles
    function viewDetalles(id) {
        // Datos simulados - En producción vendrían del servidor
        document.getElementById('detalleCambioId').textContent = id;
        document.getElementById('detalleEstado').innerHTML = '<span class="badge badge-pendiente">Pendiente</span>';
        document.getElementById('detalleEstudiante').textContent = 'Juan Pérez';
        document.getElementById('detalleMateria').textContent = 'Matemáticas';
        document.getElementById('detalleNotaAnterior').textContent = '3.2';
        document.getElementById('detalleNotaNueva').textContent = '3.8';
        document.getElementById('detalleDocente').textContent = 'Prof. García';
        document.getElementById('detalleRazon').textContent = 'Corrección de calificación';
        document.getElementById('detalleFecha').textContent = '2025-11-10';

        new bootstrap.Modal(document.getElementById('modalDetalles')).show();
    }

    // Actualizar tabla
    function refreshTable() {
        alert('🔄 Tabla actualizada (simulación).');
    }

    // Exportar
    function exportCambios() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // Búsqueda en tiempo real
    document.getElementById('searchCambios').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Filtros
    document.getElementById('filterEstado').addEventListener('change', filterTable);
    document.getElementById('filterGrado').addEventListener('change', filterTable);
    document.getElementById('filterFecha').addEventListener('change', filterTable);

    function filterTable() {
        const estado = document.getElementById('filterEstado').value;
        const grado = document.getElementById('filterGrado').value;
        const fecha = document.getElementById('filterFecha').value;
        const tableRows = document.querySelectorAll('table tbody tr');

        tableRows.forEach(row => {
            const rowEstado = row.cells[8].textContent.trim().toLowerCase();
            const rowFecha = row.cells[7].textContent.trim();
            const rowGrado = ''; // aquí aún no hay grado en tabla, se podría añadir si lo incluyes

            const estadoMatch = !estado || rowEstado.includes(estado.toLowerCase());
            const fechaMatch = !fecha || rowFecha === fecha;
            // por ahora solo estado y fecha
            row.style.display = (estadoMatch && fechaMatch) ? '' : 'none';
        });
    }
</script>
@endsection

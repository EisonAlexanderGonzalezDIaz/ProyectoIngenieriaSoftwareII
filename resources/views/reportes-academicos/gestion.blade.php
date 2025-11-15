{{-- resources/views/reportes-academicos/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GENERAR REPORTES ACADÉMICOS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Generar Reportes Académicos</h4>
            <div>
                <button class="btn btn-light btn-sm me-2" onclick="toggleGenerarReporteForm()">
                    <i class="fas fa-plus"></i> Nuevo Reporte
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="actualizarTabla()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Estadísticas --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-file me-2"></i>Generados</h5>
                            <h3 class="mb-0">24</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-download me-2"></i>Descargados</h5>
                            <h3 class="mb-0">18</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-envelope me-2"></i>Enviados</h5>
                            <h3 class="mb-0">12</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-secondary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-list me-2"></i>Total</h5>
                            <h3 class="mb-0">24</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchReportes" class="form-control" placeholder="Buscar por tipo, grado o período...">
            </div>

            {{-- Filtros --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterTipo">
                        <option value="">Filtrar por Tipo...</option>
                        <option value="Notas">Reporte de Notas</option>
                        <option value="Asistencia">Reporte de Asistencia</option>
                        <option value="Comportamiento">Reporte de Comportamiento</option>
                        <option value="Desempeño">Reporte de Desempeño</option>
                        <option value="Integral">Reporte Integral</option>
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
                    <select class="form-select form-select-sm" id="filterEstado">
                        <option value="">Filtrar por Estado...</option>
                        <option value="Generado">Generado</option>
                        <option value="Enviado">Enviado</option>
                        <option value="Descargado">Descargado</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de reportes --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Reporte</th>
                            <th>Grado</th>
                            <th>Período</th>
                            <th>Formato</th>
                            <th>Fecha Generación</th>
                            <th>Creado Por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>REP001</td>
                            <td>Reporte de Notas</td>
                            <td>10°</td>
                            <td>Período 3</td>
                            <td>PDF</td>
                            <td>2025-11-12</td>
                            <td>Prof. García</td>
                            <td><span class="badge bg-success">Generado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="descargarReporte('REP001')" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="abrirModalEnviar('REP001')" title="Enviar">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm me-1" onclick="viewDetalles('REP001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarReporte('REP001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REP002</td>
                            <td>Reporte de Asistencia</td>
                            <td>9°</td>
                            <td>Período 3</td>
                            <td>Excel</td>
                            <td>2025-11-11</td>
                            <td>Prof. López</td>
                            <td><span class="badge bg-info">Enviado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="descargarReporte('REP002')" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="abrirModalEnviar('REP002')" title="Enviar">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm me-1" onclick="viewDetalles('REP002')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarReporte('REP002')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REP003</td>
                            <td>Reporte de Comportamiento</td>
                            <td>11°</td>
                            <td>Período 2</td>
                            <td>PDF</td>
                            <td>2025-11-10</td>
                            <td>Prof. Martínez</td>
                            <td><span class="badge bg-warning text-dark">Generado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="descargarReporte('REP003')" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="abrirModalEnviar('REP003')" title="Enviar">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm me-1" onclick="viewDetalles('REP003')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarReporte('REP003')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REP004</td>
                            <td>Reporte Integral</td>
                            <td>10°</td>
                            <td>Período 1</td>
                            <td>PDF</td>
                            <td>2025-11-09</td>
                            <td>Prof. Rodríguez</td>
                            <td><span class="badge bg-success">Descargado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="descargarReporte('REP004')" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="abrirModalEnviar('REP004')" title="Enviar">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm me-1" onclick="viewDetalles('REP004')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarReporte('REP004')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REP005</td>
                            <td>Reporte de Desempeño</td>
                            <td>9°</td>
                            <td>Período 3</td>
                            <td>Excel</td>
                            <td>2025-11-08</td>
                            <td>Prof. Pérez</td>
                            <td><span class="badge bg-info">Enviado</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="descargarReporte('REP005')" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="abrirModalEnviar('REP005')" title="Enviar">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm me-1" onclick="viewDetalles('REP005')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarReporte('REP005')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <nav aria-label="Paginación reportes">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA GENERAR NUEVO REPORTE
            ============================================ --}}
            <div id="generarReporteForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus text-primary me-2"></i>Generar Nuevo Reporte</h5>
                    <button type="button" class="btn-close" onclick="toggleGenerarReporteForm()"></button>
                </div>
                <div class="card-body">
                    <form id="reporteForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Reporte <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipoReporte" required onchange="actualizarOpcionesGrado()">
                                    <option value="">Seleccionar...</option>
                                    <option value="Notas">Reporte de Notas</option>
                                    <option value="Asistencia">Reporte de Asistencia</option>
                                    <option value="Comportamiento">Reporte de Comportamiento</option>
                                    <option value="Desempeño">Reporte de Desempeño</option>
                                    <option value="Integral">Reporte Integral</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                <select class="form-select" id="gradoReporte" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="9">9°</option>
                                    <option value="10">10°</option>
                                    <option value="11">11°</option>
                                    <option value="Todos">Todos los grados</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Período <span class="text-danger">*</span></label>
                                <select class="form-select" id="periodoReporte" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Período 1">Período 1</option>
                                    <option value="Período 2">Período 2</option>
                                    <option value="Período 3">Período 3</option>
                                    <option value="Período 4">Período 4</option>
                                    <option value="Año Completo">Año Completo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Formato <span class="text-danger">*</span></label>
                                <select class="form-select" id="formatoReporte" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="PDF">PDF</option>
                                    <option value="Excel">Excel</option>
                                    <option value="CSV">CSV</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Incluir Estudiantes</label>
                                <select class="form-select" id="estudiantesReporte" multiple>
                                    <option value="Todos" selected>Todos los estudiantes</option>
                                    <option value="Juan Pérez">Juan Pérez</option>
                                    <option value="María González">María González</option>
                                    <option value="Carlos López">Carlos López</option>
                                    <option value="Ana Rodríguez">Ana Rodríguez</option>
                                </select>
                                <small class="text-muted">Mantén presionado Ctrl para seleccionar varios</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Incluir Secciones</label>
                                <select class="form-select" id="seccionesReporte" multiple>
                                    <option value="A" selected>Sección A</option>
                                    <option value="B">Sección B</option>
                                    <option value="C">Sección C</option>
                                </select>
                                <small class="text-muted">Mantén presionado Ctrl para seleccionar varios</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <input type="checkbox" id="incluirGraficos" checked> Incluir Gráficos
                            </label>
                            <br>
                            <label class="form-label">
                                <input type="checkbox" id="incluirAnalisis"> Incluir Análisis Detallado
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacionesReporte" rows="2" placeholder="Notas adicionales sobre el reporte..."></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="generarReporte()">
                                <i class="fas fa-file-export me-2"></i>Generar Reporte
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleGenerarReporteForm()">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        MODAL PARA ENVIAR REPORTE
===================================== --}}
<div class="modal fade" id="modalEnviar" tabindex="-1" aria-labelledby="modalEnviarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalEnviarLabel"><i class="fas fa-envelope me-2"></i>Enviar Reporte</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEnviar">
                    <div class="mb-3">
                        <label class="form-label">Destinatarios <span class="text-danger">*</span></label>
                        <select class="form-select" id="destinatarios" required multiple>
                            <option value="docentes">Docentes</option>
                            <option value="acudientes">Acudientes</option>
                            <option value="coordinador">Coordinador Académico</option>
                            <option value="directiva">Directiva</option>
                        </select>
                        <small class="text-muted">Mantén presionado Ctrl para seleccionar varios</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">O ingresar emails personalizados</label>
                        <textarea class="form-control" id="emailsPersonalizados" rows="3" placeholder="correo1@example.com&#10;correo2@example.com&#10;..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mensaje Personalizado</label>
                        <textarea class="form-control" id="mensajeEnvio" rows="3" placeholder="Escribe un mensaje personalizado (opcional)..."></textarea>
                    </div>
                    <input type="hidden" id="idReporteEnviar">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="confirmarEnvio()">
                    <i class="fas fa-paper-plane me-2"></i>Enviar
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
                <h5 class="modal-title" id="modalDetallesLabel"><i class="fas fa-info-circle me-2"></i>Detalles del Reporte</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Reporte:</strong> <span id="detalleId"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Estado:</strong> <span id="detalleEstado"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tipo:</strong> <span id="detalleTipo"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Grado:</strong> <span id="detalleGrado"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Período:</strong> <span id="detallePeriodo"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Formato:</strong> <span id="detalleFormato"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Creado Por:</strong> <span id="detalleCreador"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha Generación:</strong> <span id="detalleFecha"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Última Descarga:</strong> <span id="detalleUltimaDescarga"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Veces Enviado:</strong> <span id="detalleVecesEnviado"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Observaciones:</strong>
                        <p id="detalleObservaciones" class="bg-light p-2 rounded"></p>
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
    // Mostrar/Ocultar el formulario
    function toggleGenerarReporteForm() {
        const form = document.getElementById('generarReporteForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // Generar reporte
    function generarReporte() {
        const tipoReporte = document.getElementById('tipoReporte').value;
        const gradoReporte = document.getElementById('gradoReporte').value;
        const periodoReporte = document.getElementById('periodoReporte').value;
        const formatoReporte = document.getElementById('formatoReporte').value;

        if (!tipoReporte || !gradoReporte || !periodoReporte || !formatoReporte) {
            alert('Por favor, completa todos los campos requeridos.');
            return;
        }

        alert('✅ Reporte generado: ' + tipoReporte + ' - ' + gradoReporte + '° - ' + periodoReporte + ' (' + formatoReporte + ')');
        toggleGenerarReporteForm();
        document.getElementById('reporteForm').reset();
    }

    // Descargar reporte
    function descargarReporte(id) {
        alert('📥 Descargando reporte: ' + id);
        // Aquí iría la lógica para descargar el archivo
    }

    // Abrir modal para enviar
    function abrirModalEnviar(id) {
        document.getElementById('idReporteEnviar').value = id;
        document.getElementById('destinatarios').value = [];
        document.getElementById('emailsPersonalizados').value = '';
        document.getElementById('mensajeEnvio').value = '';
        new bootstrap.Modal(document.getElementById('modalEnviar')).show();
    }

    // Confirmar envío
    function confirmarEnvio() {
        const id = document.getElementById('idReporteEnviar').value;
        const destinatarios = document.getElementById('destinatarios').value;
        const emailsPersonalizados = document.getElementById('emailsPersonalizados').value;

        if (destinatarios.length === 0 && !emailsPersonalizados.trim()) {
            alert('Por favor, selecciona destinatarios o ingresa emails personalizados.');
            return;
        }

        alert('📧 Reporte enviado: ' + id);
        bootstrap.Modal.getInstance(document.getElementById('modalEnviar')).hide();
    }

    // Ver detalles
    function viewDetalles(id) {
        // Datos simulados - En producción vendrían del servidor
        document.getElementById('detalleId').textContent = id;
        document.getElementById('detalleEstado').innerHTML = '<span class="badge bg-success">Generado</span>';
        document.getElementById('detalleTipo').textContent = 'Reporte de Notas';
        document.getElementById('detalleGrado').textContent = '10°';
        document.getElementById('detallePeriodo').textContent = 'Período 3';
        document.getElementById('detalleFormato').textContent = 'PDF';
        document.getElementById('detalleCreador').textContent = 'Prof. García';
        document.getElementById('detalleFecha').textContent = '2025-11-12';
        document.getElementById('detalleUltimaDescarga').textContent = '2025-11-12 14:30';
        document.getElementById('detalleVecesEnviado').textContent = '2';
        document.getElementById('detalleObservaciones').textContent = 'Reporte con análisis completo de desempeño por materia y estudiante.';

        new bootstrap.Modal(document.getElementById('modalDetalles')).show();
    }

    // Eliminar reporte
    function eliminarReporte(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este reporte?')) {
            alert('🗑️ Reporte eliminado: ' + id);
        }
    }

    // Actualizar tabla
    function actualizarTabla() {
        alert('📊 Tabla actualizada');
    }

    // Actualizar opciones de grado según tipo de reporte
    function actualizarOpcionesGrado() {
        const tipoReporte = document.getElementById('tipoReporte').value;
        // Aquí podrías cambiar las opciones disponibles según el tipo
        console.log('Tipo de reporte seleccionado: ' + tipoReporte);
    }

    // Búsqueda en tiempo real
    document.getElementById('searchReportes').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Filtros
    document.getElementById('filterTipo').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterGrado').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterEstado').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const tipo = document.getElementById('filterTipo').value;
        const grado = document.getElementById('filterGrado').value;
        const estado = document.getElementById('filterEstado').value;
        const tableRows = document.querySelectorAll('table tbody tr');

        tableRows.forEach(row => {
            const rowTipo = row.cells[1].textContent.trim();
            const rowGrado = row.cells[2].textContent.trim();
            const rowEstado = row.cells[7].textContent.trim().toLowerCase();

            const tipoMatch = !tipo || rowTipo.includes(tipo);
            const gradoMatch = !grado || rowGrado === grado + '°';
            const estadoMatch = !estado || rowEstado.includes(estado.toLowerCase());

            row.style.display = (tipoMatch && gradoMatch && estadoMatch) ? '' : 'none';
        });
    }
</script>
@endsection
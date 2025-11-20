{{-- resources/views/recuperaciones/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE RECUPERACIONES
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-redo-alt me-2"></i>Gestión de Recuperaciones</h4>
            <div>
                <button class="btn btn-light btn-sm me-2" onclick="toggleAddRecuperacionForm()">
                    <i class="fas fa-plus"></i> Nueva Recuperación
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="exportRecuperaciones()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Estadísticas --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-hourglass-half me-2"></i>Programadas</h5>
                            <h3 class="mb-0">8</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-pencil-alt me-2"></i>Realizadas</h5>
                            <h3 class="mb-0">6</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-check me-2"></i>Aprobadas</h5>
                            <h3 class="mb-0">5</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-secondary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-list me-2"></i>Total</h5>
                            <h3 class="mb-0">19</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchRecuperaciones" class="form-control" placeholder="Buscar por estudiante, materia o docente...">
            </div>

            {{-- Filtros --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="filterEstado">
                        <option value="">Filtrar por Estado...</option>
                        <option value="Programada">Programada</option>
                        <option value="Realizada">Realizada</option>
                        <option value="Aprobada">Aprobada</option>
                        <option value="Rechazada">Rechazada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="filterGrado">
                        <option value="">Filtrar por Grado...</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                        <option value="11">11°</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" id="filterFecha" class="form-control form-control-sm" placeholder="Fecha de recuperación">
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="filterTipo">
                        <option value="">Filtrar por Tipo...</option>
                        <option value="Escrita">Escrita</option>
                        <option value="Oral">Oral</option>
                        <option value="Práctica">Práctica</option>
                        <option value="Proyecto">Proyecto</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de recuperaciones --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded text-sm">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Grado</th>
                            <th>Materia</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Docente</th>
                            <th>Nota</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>REC001</td>
                            <td>Juan Pérez</td>
                            <td>10°</td>
                            <td>Matemáticas</td>
                            <td>Escrita</td>
                            <td>2025-11-15</td>
                            <td>09:00 - 10:30</td>
                            <td>Prof. García</td>
                            <td>-</td>
                            <td><span class="badge bg-warning text-dark">Programada</span></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="viewDetalles('REC001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="abrirModalCalificar('REC001')" title="Calificar">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="editarRecuperacion('REC001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REC002</td>
                            <td>María González</td>
                            <td>9°</td>
                            <td>Español</td>
                            <td>Oral</td>
                            <td>2025-11-14</td>
                            <td>10:45 - 11:30</td>
                            <td>Prof. López</td>
                            <td>3.5</td>
                            <td><span class="badge bg-info">Realizada</span></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="viewDetalles('REC002')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm me-1" onclick="abrirModalAprobar('REC002')" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="abrirModalRechazar('REC002')" title="Rechazar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REC003</td>
                            <td>Carlos López</td>
                            <td>11°</td>
                            <td>Inglés</td>
                            <td>Escrita</td>
                            <td>2025-11-13</td>
                            <td>08:00 - 09:30</td>
                            <td>Prof. Martínez</td>
                            <td>4.0</td>
                            <td><span class="badge bg-success">Aprobada</span></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm" onclick="viewDetalles('REC003')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REC004</td>
                            <td>Ana Rodríguez</td>
                            <td>10°</td>
                            <td>Ciencias</td>
                            <td>Práctica</td>
                            <td>2025-11-12</td>
                            <td>14:00 - 15:00</td>
                            <td>Prof. Rodríguez</td>
                            <td>2.8</td>
                            <td><span class="badge bg-danger">Rechazada</span></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm" onclick="viewDetalles('REC004')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>REC005</td>
                            <td>Roberto Torres</td>
                            <td>9°</td>
                            <td>Sociales</td>
                            <td>Proyecto</td>
                            <td>2025-11-16</td>
                            <td>11:00 - 12:30</td>
                            <td>Prof. Pérez</td>
                            <td>-</td>
                            <td><span class="badge bg-warning text-dark">Programada</span></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm me-1" onclick="viewDetalles('REC005')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="abrirModalCalificar('REC005')" title="Calificar">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="editarRecuperacion('REC005')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <nav aria-label="Paginación recuperaciones">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA AGREGAR NUEVA RECUPERACIÓN
            ============================================ --}}
            <div id="addRecuperacionForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus text-danger me-2"></i>Nueva Recuperación</h5>
                    <button type="button" class="btn-close" onclick="toggleAddRecuperacionForm()"></button>
                </div>
                <div class="card-body">
                    <form id="recuperacionForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Juan Pérez</option>
                                    <option value="2">María González</option>
                                    <option value="3">Carlos López</option>
                                    <option value="4">Ana Rodríguez</option>
                                    <option value="5">Roberto Torres</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Materia <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Matemáticas</option>
                                    <option value="2">Español</option>
                                    <option value="3">Inglés</option>
                                    <option value="4">Ciencias Naturales</option>
                                    <option value="5">Sociales</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Recuperación <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Escrita">Escrita</option>
                                    <option value="Oral">Oral</option>
                                    <option value="Práctica">Práctica</option>
                                    <option value="Proyecto">Proyecto</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Docente <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Prof. García</option>
                                    <option value="2">Prof. López</option>
                                    <option value="3">Prof. Martínez</option>
                                    <option value="4">Prof. Rodríguez</option>
                                    <option value="5">Prof. Pérez</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="2" placeholder="Notas adicionales sobre la recuperación..."></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-danger" onclick="saveRecuperacion()">
                                <i class="fas fa-save me-2"></i>Guardar Recuperación
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAddRecuperacionForm()">
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
        MODAL PARA CALIFICAR RECUPERACIÓN
===================================== --}}
<div class="modal fade" id="modalCalificar" tabindex="-1" aria-labelledby="modalCalificarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCalificarLabel"><i class="fas fa-pen me-2"></i>Calificar Recuperación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCalificar">
                    <div class="mb-3">
                        <label class="form-label">Nota <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="notaCalificar" step="0.1" min="0" max="5" placeholder="Ej: 3.5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resultado <span class="text-danger">*</span></label>
                        <select class="form-select" id="resultadoCalificar" required>
                            <option value="">Seleccionar...</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Reprobado">Reprobado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios</label>
                        <textarea class="form-control" id="comentariosCalificar" rows="3" placeholder="Observaciones sobre el desempeño..."></textarea>
                    </div>
                    <input type="hidden" id="idRecuperacionCalificar">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarCalificar()">
                    <i class="fas fa-check me-2"></i>Guardar Calificación
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        MODAL PARA APROBAR RECUPERACIÓN
===================================== --}}
<div class="modal fade" id="modalAprobar" tabindex="-1" aria-labelledby="modalAprobarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAprobarLabel"><i class="fas fa-check me-2"></i>Aprobar Recuperación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAprobar">
                    <div class="mb-3">
                        <label class="form-label">Razón de Aprobación <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="razonAprobar" rows="3" placeholder="Explica por qué apruebas esta recuperación..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario Adicional</label>
                        <textarea class="form-control" id="comentarioAprobar" rows="2" placeholder="Comentarios adicionales..."></textarea>
                    </div>
                    <input type="hidden" id="idRecuperacionAprobar">
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
        MODAL PARA RECHAZAR RECUPERACIÓN
===================================== --}}
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-labelledby="modalRechazarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRechazarLabel"><i class="fas fa-times me-2"></i>Rechazar Recuperación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRechazar">
                    <div class="mb-3">
                        <label class="form-label">Razón de Rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="razonRechazar" rows="3" placeholder="Explica por qué rechazas esta recuperación..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario Adicional</label>
                        <textarea class="form-control" id="comentarioRechazar" rows="2" placeholder="Comentarios adicionales..."></textarea>
                    </div>
                    <input type="hidden" id="idRecuperacionRechazar">
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
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetallesLabel"><i class="fas fa-info-circle me-2"></i>Detalles de la Recuperación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Recuperación:</strong> <span id="detalleId"></span>
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
                        <strong>Grado:</strong> <span id="detalleGrado"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Materia:</strong> <span id="detalleMateria"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Tipo:</strong> <span id="detalleTipo"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Fecha:</strong> <span id="detalleFecha"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Hora:</strong> <span id="detalleHora"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Docente:</strong> <span id="detalleDocente"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Nota:</strong> <span id="detalleNota"></span>
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
    function toggleAddRecuperacionForm() {
        const form = document.getElementById('addRecuperacionForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // Guardar recuperación
    function saveRecuperacion() {
        alert('✅ Recuperación guardada (simulación). Luego se conectará al backend.');
        toggleAddRecuperacionForm();
        document.getElementById('recuperacionForm').reset();
    }

    // Abrir modal para calificar
    function abrirModalCalificar(id) {
        document.getElementById('idRecuperacionCalificar').value = id;
        document.getElementById('notaCalificar').value = '';
        document.getElementById('resultadoCalificar').value = '';
        document.getElementById('comentariosCalificar').value = '';
        new bootstrap.Modal(document.getElementById('modalCalificar')).show();
    }

    // Confirmar calificación
    function confirmarCalificar() {
        const id = document.getElementById('idRecuperacionCalificar').value;
        const nota = document.getElementById('notaCalificar').value;
        const resultado = document.getElementById('resultadoCalificar').value;

        if (!nota || !resultado) {
            alert('Por favor, completa todos los campos requeridos.');
            return;
        }

        alert('✅ Recuperación calificada: ' + id + '\nNota: ' + nota + '\nResultado: ' + resultado);
        bootstrap.Modal.getInstance(document.getElementById('modalCalificar')).hide();
    }

    // Abrir modal para aprobar
    function abrirModalAprobar(id) {
        document.getElementById('idRecuperacionAprobar').value = id;
        document.getElementById('razonAprobar').value = '';
        document.getElementById('comentarioAprobar').value = '';
        new bootstrap.Modal(document.getElementById('modalAprobar')).show();
    }

    // Confirmar aprobación
    function confirmarAprobar() {
        const id = document.getElementById('idRecuperacionAprobar').value;
        const razon = document.getElementById('razonAprobar').value;

        if (!razon.trim()) {
            alert('Por favor, ingresa una razón para la aprobación.');
            return;
        }

        alert('✅ Recuperación aprobada: ' + id + '\nRazón: ' + razon);
        bootstrap.Modal.getInstance(document.getElementById('modalAprobar')).hide();
    }

    // Abrir modal para rechazar
    function abrirModalRechazar(id) {
        document.getElementById('idRecuperacionRechazar').value = id;
        document.getElementById('razonRechazar').value = '';
        document.getElementById('comentarioRechazar').value = '';
        new bootstrap.Modal(document.getElementById('modalRechazar')).show();
    }

    // Confirmar rechazo
    function confirmarRechazar() {
        const id = document.getElementById('idRecuperacionRechazar').value;
        const razon = document.getElementById('razonRechazar').value;

        if (!razon.trim()) {
            alert('Por favor, ingresa una razón para el rechazo.');
            return;
        }

        alert('❌ Recuperación rechazada: ' + id + '\nRazón: ' + razon);
        bootstrap.Modal.getInstance(document.getElementById('modalRechazar')).hide();
    }

    // Ver detalles
    function viewDetalles(id) {
        // Datos simulados - En producción vendrían del servidor
        document.getElementById('detalleId').textContent = id;
        document.getElementById('detalleEstado').innerHTML = '<span class="badge bg-warning text-dark">Programada</span>';
        document.getElementById('detalleEstudiante').textContent = 'Juan Pérez';
        document.getElementById('detalleGrado').textContent = '10°';
        document.getElementById('detalleMateria').textContent = 'Matemáticas';
        document.getElementById('detalleTipo').textContent = 'Escrita';
        document.getElementById('detalleFecha').textContent = '2025-11-15';
        document.getElementById('detalleHora').textContent = '09:00 - 10:30';
        document.getElementById('detalleDocente').textContent = 'Prof. García';
        document.getElementById('detalleNota').textContent = '-';
        document.getElementById('detalleObservaciones').textContent = 'Recuperación programada para estudiante que no asistió a evaluación anterior.';

        new bootstrap.Modal(document.getElementById('modalDetalles')).show();
    }

    // Editar recuperación
    function editarRecuperacion(id) {
        alert('✏️ Editando recuperación: ' + id);
        // Aquí iría la lógica para cargar el formulario con los datos
    }

    // Exportar
    function exportRecuperaciones() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // Búsqueda en tiempo real
    document.getElementById('searchRecuperaciones').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Filtros
    document.getElementById('filterEstado').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterGrado').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterFecha').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterTipo').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const estado = document.getElementById('filterEstado').value;
        const grado = document.getElementById('filterGrado').value;
        const fecha = document.getElementById('filterFecha').value;
        const tipo = document.getElementById('filterTipo').value;
        const tableRows = document.querySelectorAll('table tbody tr');

        tableRows.forEach(row => {
            const rowEstado = row.cells[9].textContent.trim().toLowerCase();
            const rowGrado = row.cells[2].textContent.trim();
            const rowFecha = row.cells[5].textContent.trim();
            const rowTipo = row.cells[4].textContent.trim();

            const estadoMatch = !estado || rowEstado.includes(estado.toLowerCase());
            const gradoMatch = !grado || rowGrado === grado + '°';
            const fechaMatch = !fecha || rowFecha === fecha;
            const tipoMatch = !tipo || rowTipo === tipo;

            row.style.display = (estadoMatch && gradoMatch && fechaMatch && tipoMatch) ? '' : 'none';
        });
    }
</script>
@endsection
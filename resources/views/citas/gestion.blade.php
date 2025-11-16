@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE CITAS A ACUDIENTES
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <div class="d-flex align-items-center">
                <span class="me-2">
                    <i class="fas fa-users fa-lg"></i>
                </span>
                <div>
                    <h4 class="mb-0 fw-semibold">Citar Acudientes</h4>
                    <small class="text-light-50">Gestión de citaciones con padres y acudientes</small>
                </div>
            </div>

            <div class="d-flex align-items-center">
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i> Panel de inicio
                </a>

                <button class="btn btn-light btn-sm d-flex align-items-center" onclick="toggleFormCitar()">
                    <i class="fas fa-plus me-1"></i> Nueva Citación
                </button>
            </div>
        </div>

        <div class="card-body bg-light">

            {{-- Filtros y búsqueda --}}
            <div class="row mb-4">
                <div class="col-md-7">
                    <label class="form-label text-muted small mb-1">
                        Buscar citas
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input 
                            type="text" 
                            id="searchCitas" 
                            class="form-control border-start-0" 
                            placeholder="Buscar por estudiante, acudiente, motivo o estado...">
                    </div>
                </div>

                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <label class="form-label text-muted small mb-1 d-block text-start text-md-end">
                        Filtro rápido
                    </label>
                    <div class="d-flex justify-content-md-end gap-2">
                        <select id="filterEstado" class="form-select form-select-sm w-auto shadow-sm">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Asistió">Asistió</option>
                            <option value="No asistió">No asistió</option>
                            <option value="Reprogramada">Reprogramada</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tabla de citas (datos de ejemplo, estáticos) --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded overflow-hidden">
                    <thead class="table-primary">
                        <tr class="text-center text-nowrap">
                            <th class="small">#</th>
                            <th class="small text-start">Estudiante</th>
                            <th class="small">Grado</th>
                            <th class="small text-start">Acudiente</th>
                            <th class="small">Fecha</th>
                            <th class="small">Hora</th>
                            <th class="small text-start">Motivo</th>
                            <th class="small">Estado</th>
                            <th class="small">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCitas">
                        <tr>
                            <td class="text-center text-muted">1</td>
                            <td>
                                <div class="fw-semibold">Juan Pérez</div>
                                <small class="text-muted">ID: EST001</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border">
                                    10°A
                                </span>
                            </td>
                            <td>
                                <div>Carolina Pérez</div>
                                <small class="text-muted">Madre</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-11-20
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    08:00
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    Bajo rendimiento académico
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark px-3">
                                    Pendiente
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCita(1)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="reprogramarCita(1)" title="Reprogramar">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="marcarAsistio(1)" title="Marcar como asistió">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center text-muted">2</td>
                            <td>
                                <div class="fw-semibold">María González</div>
                                <small class="text-muted">ID: EST002</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border">
                                    9°B
                                </span>
                            </td>
                            <td>
                                <div>Pedro González</div>
                                <small class="text-muted">Padre</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-11-22
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    10:30
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    Comportamiento en clase
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success px-3">
                                    Asistió
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCita(2)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="reprogramarCita(2)" title="Reprogramar">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="marcarAsistio(2)" title="Marcar como asistió">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center text-muted">3</td>
                            <td>
                                <div class="fw-semibold">Carlos López</div>
                                <small class="text-muted">ID: EST003</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border">
                                    8°C
                                </span>
                            </td>
                            <td>
                                <div>Laura López</div>
                                <small class="text-muted">Madre</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-11-25
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    09:15
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    Incumplimiento del manual de convivencia
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger px-3">
                                    No asistió
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCita(3)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="reprogramarCita(3)" title="Reprogramar">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="marcarAsistio(3)" title="Marcar como asistió">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación (maqueta) --}}
            <nav aria-label="Paginación citas">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item disabled">
                        <a class="page-link">Anterior</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA NUEVA CITACIÓN (MAQUETA)
            ============================================ --}}
            <div id="formCitarWrapper" class="card shadow-sm border-0 mt-4" style="display: none;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary border me-2">
                            <i class="fas fa-envelope-open-text"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-semibold text-primary">Nueva citación a acudiente</h5>
                            <small class="text-muted">Complete la información para registrar la citación</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="toggleFormCitar()"></button>
                </div>

                <div class="card-body bg-light">
                    {{-- Importante: action="#" para que NO requiera ruta aún --}}
                    <form action="#" method="POST" id="formCitar">
                        {{-- @csrf --}}

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-secondary small fw-semibold">
                                    Estudiante
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-user-graduate text-primary"></i>
                                    </span>
                                    <select name="student_id" class="form-select" required>
                                        <option value="">Seleccionar estudiante...</option>
                                        <option value="1">Juan Pérez — 10°A</option>
                                        <option value="2">María González — 9°B</option>
                                        <option value="3">Carlos López — 8°C</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="form-label text-secondary small fw-semibold">
                                    Fecha
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-secondary small fw-semibold">
                                    Hora
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-clock text-primary"></i>
                                    </span>
                                    <input type="time" name="hora" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">
                                Motivo de la citación
                            </label>
                            <textarea 
                                name="motivo" 
                                rows="3" 
                                class="form-control" 
                                placeholder="Describe brevemente el motivo de la citación (ej. bajo rendimiento, comportamiento, seguimiento, etc.)"
                                required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">
                                Observaciones adicionales (opcional)
                            </label>
                            <textarea 
                                name="observaciones" 
                                rows="2" 
                                class="form-control" 
                                placeholder="Notas adicionales para el acudiente o para el registro interno del colegio..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button 
                                type="button" 
                                class="btn btn-primary d-flex align-items-center"
                                onclick="simularEnvioCita()">
                                <i class="fas fa-paper-plane me-2"></i>
                                Guardar citación
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-outline-secondary"
                                onclick="toggleFormCitar()">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ==============================
        SCRIPTS (solo front)
============================== --}}
<script>
    function toggleFormCitar() {
        const card = document.getElementById('formCitarWrapper');
        if (card.style.display === 'none' || card.style.display === '') {
            card.style.display = 'block';
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        } else {
            card.style.display = 'none';
        }
    }

    function simularEnvioCita() {
        alert('✅ Citación registrada (simulación). Más adelante se conectará al backend.');
        const form = document.getElementById('formCitar');
        if (form) {
            form.reset();
        }
        toggleFormCitar();
    }

    // Funciones simuladas para los botones de la tabla
    function verCita(id) {
        alert('👁️ Viendo detalles de la cita ID: ' + id + ' (simulación)');
    }

    function reprogramarCita(id) {
        alert('📅 Reprogramar cita ID: ' + id + ' (simulación)');
    }

    function marcarAsistio(id) {
        alert('✅ Marcando como asistió la cita ID: ' + id + ' (simulación)');
    }

    // Búsqueda sencilla en la tabla (en front)
    document.getElementById('searchCitas').addEventListener('keyup', function (e) {
        const value = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaCitas tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // Filtro por estado (sencillo en front)
    document.getElementById('filterEstado').addEventListener('change', function () {
        const estado = this.value;
        const rows = document.querySelectorAll('#tablaCitas tr');

        rows.forEach(row => {
            const badge = row.querySelector('td:nth-child(8) .badge');
            if (!badge) return;

            const text = badge.textContent.trim();
            if (!estado || text === estado) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

{{-- Estilos finos para mantener la misma línea visual --}}
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
        transition: all 0.2s ease;
    }

    .badge {
        font-size: 0.8rem;
    }

    .bg-primary-subtle {
        background-color: rgba(13,110,253,0.08);
    }
</style>
@endsection

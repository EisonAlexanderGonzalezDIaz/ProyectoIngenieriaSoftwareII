@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE CASOS DISCIPLINARIOS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <div class="d-flex align-items-center">
                <span class="me-2">
                    <i class="fas fa-balance-scale fa-lg"></i>
                </span>
                <div>
                    <h4 class="mb-0 fw-semibold">Casos disciplinarios</h4>
                    <small class="text-light-50">Registro y seguimiento de casos de convivencia escolar</small>
                </div>
            </div>

            <div class="d-flex align-items-center">
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i> Panel de inicio
                </a>

                <button class="btn btn-light btn-sm d-flex align-items-center" onclick="toggleFormCaso()">
                    <i class="fas fa-plus me-1"></i> Nuevo caso
                </button>
            </div>
        </div>

        <div class="card-body bg-light">

            {{-- Filtros y búsqueda --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small mb-1">
                        Buscar casos
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input
                            type="text"
                            id="searchCasos"
                            class="form-control border-start-0"
                            placeholder="Buscar por estudiante, tipo de falta, estado...">
                    </div>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <label class="form-label text-muted small mb-1 d-block text-start text-md-end">
                        Filtros rápidos
                    </label>
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        <select id="filterGravedad" class="form-select form-select-sm w-auto shadow-sm">
                            <option value="">Gravedad (todas)</option>
                            <option value="Leve">Leve</option>
                            <option value="Media">Media</option>
                            <option value="Grave">Grave</option>
                        </select>
                        <select id="filterEstadoCaso" class="form-select form-select-sm w-auto shadow-sm">
                            <option value="">Estado (todos)</option>
                            <option value="Abierto">Abierto</option>
                            <option value="En seguimiento">En seguimiento</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tabla de casos (datos de ejemplo, estáticos) --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded overflow-hidden">
                    <thead class="table-primary">
                        <tr class="text-center text-nowrap">
                            <th class="small">#</th>
                            <th class="small text-start">Estudiante</th>
                            <th class="small">Grado</th>
                            <th class="small text-start">Tipo de falta</th>
                            <th class="small">Fecha</th>
                            <th class="small">Gravedad</th>
                            <th class="small">Estado</th>
                            <th class="small">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCasos">
                        {{-- Caso 1 --}}
                        <tr data-gravedad="Media" data-estado="Abierto">
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
                                <small class="text-muted">
                                    Irrespeto al docente en clase
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-11-10
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark px-3">
                                    Media
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-3">
                                    Abierto
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCaso(1)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="seguirCaso(1)" title="Registrar seguimiento">
                                        <i class="fas fa-notes-medical"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="cerrarCaso(1)" title="Cerrar caso">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Caso 2 --}}
                        <tr data-gravedad="Leve" data-estado="Cerrado">
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
                                <small class="text-muted">
                                    Uso inadecuado del celular en clase
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-10-30
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success px-3">
                                    Leve
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary px-3">
                                    Cerrado
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCaso(2)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="seguirCaso(2)" title="Registrar seguimiento">
                                        <i class="fas fa-notes-medical"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="cerrarCaso(2)" title="Cerrar caso">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Caso 3 --}}
                        <tr data-gravedad="Grave" data-estado="En seguimiento">
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
                                <small class="text-muted">
                                    Agresión física a compañero
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    2025-11-05
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger px-3">
                                    Grave
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark px-3">
                                    En seguimiento
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="verCaso(3)" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="seguirCaso(3)" title="Registrar seguimiento">
                                        <i class="fas fa-notes-medical"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="cerrarCaso(3)" title="Cerrar caso">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación (maqueta) --}}
            <nav aria-label="Paginación casos">
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
                 FORMULARIO PARA NUEVO CASO (MAQUETA)
            ============================================ --}}
            <div id="formCasoWrapper" class="card shadow-sm border-0 mt-4" style="display: none;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary border me-2">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-semibold text-primary">Registrar nuevo caso disciplinario</h5>
                            <small class="text-muted">Maqueta de formulario, aún sin conexión a base de datos</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="toggleFormCaso()"></button>
                </div>

                <div class="card-body bg-light">
                    {{-- Importante: action="#" para no requerir rutas aún --}}
                    <form action="#" method="POST" id="formCaso">
                        {{-- @csrf --}}

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-secondary small fw-semibold">Estudiante</label>
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

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-secondary small fw-semibold">Fecha del hecho</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                    </span>
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-semibold">Gravedad</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-thermometer-half text-primary"></i>
                                    </span>
                                    <select name="gravedad" class="form-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Leve">Leve</option>
                                        <option value="Media">Media</option>
                                        <option value="Grave">Grave</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Tipo de falta</label>
                            <input
                                type="text"
                                name="tipo_falta"
                                class="form-control"
                                placeholder="Ejemplo: Irrespeto al docente, agresión física, daño en propiedad, etc."
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Descripción de los hechos</label>
                            <textarea
                                name="descripcion"
                                rows="3"
                                class="form-control"
                                placeholder="Describa brevemente qué sucedió, quiénes estuvieron involucrados, lugar y hora."
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Medidas tomadas inicialmente</label>
                            <textarea
                                name="medidas"
                                rows="2"
                                class="form-control"
                                placeholder="Ejemplo: Llamado de atención, registro en observador, remisión a orientación, etc."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-semibold">Estado del caso</label>
                            <select name="estado" class="form-select w-auto">
                                <option value="Abierto">Abierto</option>
                                <option value="En seguimiento">En seguimiento</option>
                                <option value="Cerrado">Cerrado</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button
                                type="button"
                                class="btn btn-primary d-flex align-items-center"
                                onclick="simularGuardarCaso()">
                                <i class="fas fa-save me-2"></i>
                                Guardar casos
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="toggleFormCaso()">
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
    function toggleFormCaso() {
        const card = document.getElementById('formCasoWrapper');
        if (card.style.display === 'none' || card.style.display === '') {
            card.style.display = 'block';
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        } else {
            card.style.display = 'none';
        }
    }

    function simularGuardarCaso() {
        alert('✅ Caso disciplinario registrado (simulación). Más adelante se conectará al backend.');
        const form = document.getElementById('formCaso');
        if (form) {
            form.reset();
        }
        toggleFormCaso();
    }

    // Funciones simuladas para los botones de la tabla
    function verCaso(id) {
        alert('👁️ Viendo detalles del caso ID: ' + id + ' (simulación)');
    }

    function seguirCaso(id) {
        alert('📝 Registrando seguimiento para el caso ID: ' + id + ' (simulación)');
    }

    function cerrarCaso(id) {
        alert('🔒 Cerrando el caso ID: ' + id + ' (simulación)');
    }

    // Búsqueda sencilla en la tabla (front)
    document.getElementById('searchCasos').addEventListener('keyup', function (e) {
        const value = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaCasos tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // Filtros por gravedad y estado (front)
    document.getElementById('filterGravedad').addEventListener('change', filtrarCasos);
    document.getElementById('filterEstadoCaso').addEventListener('change', filtrarCasos);

    function filtrarCasos() {
        const gravedad = document.getElementById('filterGravedad').value;
        const estado  = document.getElementById('filterEstadoCaso').value;
        const rows    = document.querySelectorAll('#tablaCasos tr');

        rows.forEach(row => {
            const rowGravedad = row.getAttribute('data-gravedad');
            const rowEstado   = row.getAttribute('data-estado');

            const coincideGravedad = !gravedad || rowGravedad === gravedad;
            const coincideEstado   = !estado || rowEstado === estado;

            row.style.display = (coincideGravedad && coincideEstado) ? '' : 'none';
        });
    }
</script>

{{-- Estilos finos para mantener línea azul/gris --}}
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

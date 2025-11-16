{{-- resources/views/orientacion/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE ORIENTACIONES
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0">
                <i class="fas fa-book-reader me-2"></i>Gestión de Orientaciones
            </h4>
            <div>
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>

                {{-- Botones rápidos (por ahora sin rutas reales) --}}
                <a href="#" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-exclamation-triangle"></i> Casos graves
                </a>
                <a href="#" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-eye"></i> Seguimiento
                </a>
                <a href="#" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-user-clock"></i> Sesiones
                </a>
            </div>
        </div>

        <div class="card-body" style="background-color:#f4f8ff;">

            {{-- Resumen general (estadísticas rápidas simuladas) --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small">Casos activos</h6>
                            <h3 class="mb-1 text-primary fw-bold">18</h3>
                            <p class="small text-muted mb-0">en seguimiento</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small">Casos graves</h6>
                            <h3 class="mb-1 fw-bold" style="color:#0d47a1;">4</h3>
                            <p class="small text-muted mb-0">requieren prioridad</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small">Sesiones hoy</h6>
                            <h3 class="mb-1 text-info fw-bold">5</h3>
                            <p class="small text-muted mb-0">agendadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small">Cerrados este mes</h6>
                            <h3 class="mb-1 text-secondary fw-bold">12</h3>
                            <p class="small text-muted mb-0">casos resueltos</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra de búsqueda / filtros de casos --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body bg-white">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-secondary small mb-1">
                                Buscar estudiante / caso
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchCasos" class="form-control" placeholder="Nombre del estudiante, código o tema...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small mb-1">
                                Tipo de caso
                            </label>
                            <select class="form-select form-select-sm" id="filterTipo">
                                <option value="">Todos</option>
                                <option value="Convivencia">Convivencia</option>
                                <option value="Académico">Académico</option>
                                <option value="Emocional">Emocional</option>
                                <option value="Familiar">Familiar</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small mb-1">
                                Nivel de riesgo
                            </label>
                            <select class="form-select form-select-sm" id="filterRiesgo">
                                <option value="">Todos</option>
                                <option value="Alto">Alto</option>
                                <option value="Medio">Medio</option>
                                <option value="Bajo">Bajo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-secondary small mb-1">
                                Estado
                            </label>
                            <select class="form-select form-select-sm" id="filterEstadoCaso">
                                <option value="">Todos</option>
                                <option value="Activo">Activo</option>
                                <option value="En seguimiento">En seguimiento</option>
                                <option value="Cerrado">Cerrado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de casos de orientación --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded" id="tablaCasos">
                    <thead style="background-color:#1976d2; color:white;">
                        <tr>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>Grado</th>
                            <th>Tipo de caso</th>
                            <th>Riesgo</th>
                            <th>Estado</th>
                            <th>Última sesión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ejemplos estáticos de casos --}}
                        <tr>
                            <td>CAS001</td>
                            <td>Juan Pérez</td>
                            <td>10°</td>
                            <td>Convivencia</td>
                            <td><span class="badge" style="background-color:#0d47a1;">Alto</span></td>
                            <td><span class="badge bg-info text-dark">En seguimiento</span></td>
                            <td>10/11/2025</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm mb-1" onclick="verDetallesCaso('CAS001')" title="Ver detalle">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm mb-1" onclick="agendarSesion('CAS001')" title="Agendar sesión">
                                    <i class="fas fa-calendar-plus"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm mb-1" onclick="registrarSeguimiento('CAS001')" title="Registrar seguimiento">
                                    <i class="fas fa-clipboard-check"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAS002</td>
                            <td>María Gómez</td>
                            <td>9°</td>
                            <td>Académico</td>
                            <td><span class="badge bg-primary-subtle text-dark" style="background-color:#90caf9;">Medio</span></td>
                            <td><span class="badge bg-primary">Activo</span></td>
                            <td>08/11/2025</td>
                            <td>
                                <button class="btn btn-outline-info btn-sm mb-1" onclick="verDetallesCaso('CAS002')" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm mb-1" onclick="agendarSesion('CAS002')" title="Agendar sesión">
                                    <i class="fas fa-calendar-plus"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm mb-1" onclick="registrarSeguimiento('CAS002')" title="Registrar seguimiento">
                                    <i class="fas fa-clipboard-check"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAS003</td>
                            <td>Carlos López</td>
                            <td>11°</td>
                            <td>Emocional</td>
                            <td><span class="badge" style="background-color:#0d47a1;">Alto</span></td>
                            <td><span class="badge bg-primary">Activo</span></td>
                            <td>09/11/2025</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm mb-1" onclick="verDetallesCaso('CAS003')" title="Ver detalle">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm mb-1" onclick="agendarSesion('CAS003')" title="Agendar sesión">
                                    <i class="fas fa-calendar-plus"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm mb-1" onclick="registrarSeguimiento('CAS003')" title="Registrar seguimiento">
                                    <i class="fas fa-clipboard-check"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CAS004</td>
                            <td>Luisa Fernández</td>
                            <td>8°</td>
                            <td>Familiar</td>
                            <td><span class="badge bg-info text-dark">Bajo</span></td>
                            <td><span class="badge bg-secondary">Cerrado</span></td>
                            <td>02/11/2025</td>
                            <td>
                                <button class="btn btn-outline-info btn-sm mb-1" onclick="verDetallesCaso('CAS004')" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación simulada --}}
            <nav aria-label="Paginación casos">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- Agenda del día (simulada) --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-secondary">
                        <i class="fas fa-user-clock me-2"></i> Agenda de sesiones del día
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" type="button">
                        Ver toda la agenda
                    </button>
                </div>
                <div class="card-body" style="background-color:#e3f2fd;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>09:00 am</strong> - Juan Pérez (10°)
                                <br>
                                <span class="small text-muted">Seguimiento caso de convivencia</span>
                            </div>
                            <span class="badge bg-primary">Alto</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>10:30 am</strong> - María Gómez (9°)
                                <br>
                                <span class="small text-muted">Dificultades académicas</span>
                            </div>
                            <span class="badge bg-info text-dark">Medio</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>02:00 pm</strong> - Carlos López (11°)
                                <br>
                                <span class="small text-muted">Apoyo emocional</span>
                            </div>
                            <span class="badge" style="background-color:#0d47a1;">Alto</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div> {{-- card-body --}}
    </div> {{-- card --}}
</div> {{-- container --}}

{{-- =====================================
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    // Búsqueda en tiempo real
    document.getElementById('searchCasos').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tablaCasos tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Filtros
    document.getElementById('filterTipo').addEventListener('change', filterCasos);
    document.getElementById('filterRiesgo').addEventListener('change', filterCasos);
    document.getElementById('filterEstadoCaso').addEventListener('change', filterCasos);

    function filterCasos() {
        const tipo = document.getElementById('filterTipo').value;
        const riesgo = document.getElementById('filterRiesgo').value;
        const estado = document.getElementById('filterEstadoCaso').value;

        const tableRows = document.querySelectorAll('#tablaCasos tbody tr');

        tableRows.forEach(row => {
            const rowTipo   = row.cells[3].textContent.trim(); // Tipo de caso
            const rowRiesgo = row.cells[4].textContent.trim(); // Riesgo
            const rowEstado = row.cells[5].textContent.trim(); // Estado

            const tipoMatch   = !tipo   || rowTipo === tipo;
            const riesgoMatch = !riesgo || rowRiesgo === riesgo;
            const estadoMatch = !estado || rowEstado.includes(estado);

            row.style.display = (tipoMatch && riesgoMatch && estadoMatch) ? '' : 'none';
        });
    }

    // Simulaciones de acciones
    function verDetallesCaso(codigo) {
        alert('🔍 Viendo detalles del caso: ' + codigo + '\n(Aquí se abrirá una vista detallada con la historia del caso).');
    }

    function agendarSesion(codigo) {
        alert('🗓️ Agendando nueva sesión para el caso: ' + codigo + '\n(Luego se conectará al módulo de agenda).');
    }

    function registrarSeguimiento(codigo) {
        alert('📋 Registrando seguimiento para el caso: ' + codigo + '\n(Aquí se abrirá un formulario de registro de seguimiento).');
    }
</script>
@endsection

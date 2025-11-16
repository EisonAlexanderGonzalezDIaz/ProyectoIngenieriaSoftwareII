@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         REPORTES DISCIPLINARIOS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>
                Generar Reportes Disciplinarios
            </h4>

            <div>
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>

                {{-- Botón "simulado" para exportar --}}
                <button class="btn btn-light btn-sm" onclick="simularExportarReporte()">
                    <i class="fas fa-file-export"></i> Exportar resumen
                </button>
            </div>
        </div>

        <div class="card-body bg-light">

            {{-- Filtros para reportes --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-filter me-2"></i>
                        Filtros para generar reporte
                    </h5>
                </div>
                <div class="card-body">
                    {{-- IMPORTANTE: action="#" para que NO dependa del backend aún --}}
                    <form action="#" method="GET" id="formFiltrosReporte">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label text-secondary small">Rango de fechas</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="fecha_desde" class="form-control">
                                </div>
                                <small class="text-muted">Desde</small>
                            </div>

                            <div class="col-md-3 mt-4 mt-md-0">
                                <label class="form-label text-secondary small">&nbsp;</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    <input type="date" name="fecha_hasta" class="form-control">
                                </div>
                                <small class="text-muted">Hasta</small>
                            </div>

                            <div class="col-md-3 mt-3 mt-md-0">
                                <label class="form-label text-secondary small">Grado</label>
                                <select name="grado" class="form-select">
                                    <option value="">Todos los grados</option>
                                    <option>6°</option>
                                    <option>7°</option>
                                    <option>8°</option>
                                    <option>9°</option>
                                    <option>10°</option>
                                    <option>11°</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3 mt-md-0">
                                <label class="form-label text-secondary small">Tipo de falta</label>
                                <select name="tipo_falta" class="form-select">
                                    <option value="">Todas</option>
                                    <option>Leve</option>
                                    <option>Grave</option>
                                    <option>Gravísima</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small">Estado del caso</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option>Abierto</option>
                                    <option>En seguimiento</option>
                                    <option>Cerrado</option>
                                </select>
                            </div>

                            <div class="col-md-4 mt-3 mt-md-0">
                                <label class="form-label text-secondary small">Formato de reporte</label>
                                <select name="formato" class="form-select">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="pantalla">Solo vista en pantalla</option>
                                </select>
                            </div>

                            <div class="col-md-4 mt-3 mt-md-0 d-flex align-items-end justify-content-md-end">
                                <button type="button" class="btn btn-primary me-2" onclick="simularGenerarReporte()">
                                    <i class="fas fa-file-alt me-1"></i> Generar reporte
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltrosReporte()">
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Resumen visual (maqueta) --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-secondary small mb-1">Total casos</p>
                            <h3 class="text-primary mb-0">24</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-secondary small mb-1">Casos abiertos</p>
                            <h3 class="text-warning mb-0">7</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-secondary small mb-1">Casos cerrados</p>
                            <h3 class="text-success mb-0">15</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-secondary small mb-1">% casos graves</p>
                            <h3 class="text-danger mb-0">30%</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de vista previa de casos (datos de ejemplo) --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-list me-2"></i>
                        Vista previa de casos disciplinarios
                    </h5>
                    <small class="text-muted">Datos de ejemplo (maqueta)</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Grado</th>
                                    <th>Tipo de falta</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Medida tomada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Juan Pérez</td>
                                    <td>10°A</td>
                                    <td>Grave</td>
                                    <td>2025-11-10</td>
                                    <td><span class="badge bg-warning text-dark">En seguimiento</span></td>
                                    <td>Llamado a acudiente</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>María González</td>
                                    <td>9°B</td>
                                    <td>Leve</td>
                                    <td>2025-11-08</td>
                                    <td><span class="badge bg-success">Cerrado</span></td>
                                    <td>Compromiso escrito</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Carlos López</td>
                                    <td>8°C</td>
                                    <td>Gravísima</td>
                                    <td>2025-11-05</td>
                                    <td><span class="badge bg-danger">Abierto</span></td>
                                    <td>Remitido al Consejo Directivo</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Laura Martínez</td>
                                    <td>11°A</td>
                                    <td>Grave</td>
                                    <td>2025-11-01</td>
                                    <td><span class="badge bg-success">Cerrado</span></td>
                                    <td>Suspensión 1 día</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Paginación de ejemplo --}}
                <div class="card-footer bg-white">
                    <nav aria-label="Paginación reportes">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ==============================
        SCRIPTS (solo front)
============================== --}}
<script>
    function simularGenerarReporte() {
        alert('📄 Reporte generado (simulación). Más adelante se conectará al backend para generar PDF/Excel.');
    }

    function limpiarFiltrosReporte() {
        document.getElementById('formFiltrosReporte').reset();
    }

    function simularExportarReporte() {
        alert('⬇️ Exportación de reporte (simulación). Luego se integrará con la generación real de archivos.');
    }
</script>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transition: all 0.2s ease;
    }

    .card-header h5,
    .card-header h4 {
        font-weight: 600;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.55rem;
    }
</style>
@endsection

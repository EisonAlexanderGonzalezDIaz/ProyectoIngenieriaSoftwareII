{{-- resources/views/planacademico/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .page-header {
        background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13,110,253,0.04);
    }

    .nav-pills .nav-link {
        border-radius: 999px;
        color: #0d6efd;
        background-color: #e7f1ff;
        margin-right: .5rem;
        font-weight: 500;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.15);
    }

    .badge-periodo {
        background-color: #0d6efd;
        color: #fff;
    }

    .badge-obligatoria {
        background-color: #198754;
        color: #fff;
    }

    .badge-optativa {
        background-color: #6c757d;
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header page-header rounded-top d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-book-open me-2"></i>Consultar Plan Académico
            </h4>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>
                <button class="btn btn-outline-light btn-sm" onclick="exportPlan()">
                    <i class="fas fa-file-export"></i> Exportar plan
                </button>
            </div>
        </div>

        <div class="card-body bg-light">

            {{-- Filtros principales --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label mb-1">Grado</label>
                    <select id="filtroGradoPlan" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                        <option value="11">11°</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Grupo</label>
                    <select id="filtroGrupoPlan" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Año</label>
                    <select id="filtroAnioPlan" class="form-select form-select-sm">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Área</label>
                    <select id="filtroAreaPlan" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="Matemáticas">Matemáticas</option>
                        <option value="Lengua Castellana">Lengua Castellana</option>
                        <option value="Ciencias">Ciencias</option>
                        <option value="Inglés">Inglés</option>
                    </select>
                </div>
            </div>

            {{-- Búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="buscarPlan" class="form-control" placeholder="Buscar por materia, competencia o estándar...">
            </div>

            {{-- Tabs de vista --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="nav nav-pills" id="planTabs" role="tablist">
                    <button class="nav-link active" id="tab-materias-tab" data-bs-toggle="pill" data-bs-target="#tab-materias" type="button" role="tab">
                        <i class="fas fa-list-ul me-1"></i>Por materias
                    </button>
                    <button class="nav-link" id="tab-periodos-tab" data-bs-toggle="pill" data-bs-target="#tab-periodos" type="button" role="tab">
                        <i class="fas fa-calendar-alt me-1"></i>Por periodos
                    </button>
                </div>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Vista solo informativa (maqueta).
                </small>
            </div>

            <div class="tab-content" id="planTabsContent">
                {{-- TAB MATERIAS --}}
                <div class="tab-pane fade show active" id="tab-materias" role="tabpanel" aria-labelledby="tab-materias-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle bg-white shadow-sm rounded" id="tablaPlanMaterias">
                            <thead class="table-primary">
                                <tr>
                                    <th>Grado</th>
                                    <th>Materia</th>
                                    <th>Tipo</th>
                                    <th>Área</th>
                                    <th>Periodo</th>
                                    <th>Competencias</th>
                                    <th>Estándares</th>
                                    <th>Intensidad (h/sem)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11°</td>
                                    <td>Matemáticas Avanzadas</td>
                                    <td><span class="badge badge-obligatoria">Obligatoria</span></td>
                                    <td>Matemáticas</td>
                                    <td><span class="badge badge-periodo">1 - 4</span></td>
                                    <td>Razonamiento y resolución de problemas</td>
                                    <td>Estándares MEN grado 11 Matemáticas</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td>11°</td>
                                    <td>Inglés B2</td>
                                    <td><span class="badge badge-optativa">Optativa</span></td>
                                    <td>Inglés</td>
                                    <td><span class="badge badge-periodo">1 - 4</span></td>
                                    <td>Comprensión lectora y producción escrita</td>
                                    <td>Estándares MEN grado 11 Inglés</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>10°</td>
                                    <td>Lengua Castellana</td>
                                    <td><span class="badge badge-obligatoria">Obligatoria</span></td>
                                    <td>Lengua Castellana</td>
                                    <td><span class="badge badge-periodo">1 - 4</span></td>
                                    <td>Comprensión de textos argumentativos</td>
                                    <td>Estándares MEN grado 10 Lengua Castellana</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>9°</td>
                                    <td>Ciencias Naturales</td>
                                    <td><span class="badge badge-obligatoria">Obligatoria</span></td>
                                    <td>Ciencias</td>
                                    <td><span class="badge badge-periodo">1 - 4</span></td>
                                    <td>Pensamiento científico y experimental</td>
                                    <td>Estándares MEN grado 9 Ciencias Naturales</td>
                                    <td>4</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB PERIODOS --}}
                <div class="tab-pane fade" id="tab-periodos" role="tabpanel" aria-labelledby="tab-periodos-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle bg-white shadow-sm rounded" id="tablaPlanPeriodos">
                            <thead class="table-primary">
                                <tr>
                                    <th>Periodo</th>
                                    <th>Grado</th>
                                    <th>Materia</th>
                                    <th>Competencias a desarrollar</th>
                                    <th>Contenidos clave</th>
                                    <th>Estrategias de evaluación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-periodo">1</span></td>
                                    <td>11°</td>
                                    <td>Matemáticas Avanzadas</td>
                                    <td>Resolver problemas con funciones y derivadas</td>
                                    <td>Funciones, límites, derivadas básicas</td>
                                    <td>Pruebas escritas, proyectos y talleres</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-periodo">2</span></td>
                                    <td>11°</td>
                                    <td>Matemáticas Avanzadas</td>
                                    <td>Analizar comportamiento de funciones</td>
                                    <td>Derivadas aplicadas, máximos y mínimos</td>
                                    <td>Laboratorios, evaluaciones parciales</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-periodo">1</span></td>
                                    <td>10°</td>
                                    <td>Lengua Castellana</td>
                                    <td>Comprender textos argumentativos</td>
                                    <td>Tipos de argumentos, cohesión y coherencia</td>
                                    <td>Ensayos, exposiciones orales</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-periodo">3</span></td>
                                    <td>9°</td>
                                    <td>Ciencias Naturales</td>
                                    <td>Aplicar método científico en problemas reales</td>
                                    <td>Experimentos, análisis de datos</td>
                                    <td>Informes de laboratorio, rúbricas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> {{-- /tab-content --}}
        </div>
    </div>
</div>

{{-- SCRIPTS (solo front / maqueta) --}}
<script>
    function exportPlan() {
        alert('📁 Exportación simulada del plan académico (PDF/Excel).');
    }

    // Búsqueda general sobre ambas tablas
    document.getElementById('buscarPlan').addEventListener('keyup', function(e) {
        const q = e.target.value.toLowerCase();
        const tablas = ['tablaPlanMaterias', 'tablaPlanPeriodos'];

        tablas.forEach(idTabla => {
            const filas = document.querySelectorAll('#' + idTabla + ' tbody tr');
            filas.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });

    // Filtros simples (grado / área) sobre la tabla de materias
    document.getElementById('filtroGradoPlan').addEventListener('change', filtrarPlanMaterias);
    document.getElementById('filtroAreaPlan').addEventListener('change', filtrarPlanMaterias);
    document.getElementById('filtroGrupoPlan').addEventListener('change', filtrarPlanMaterias);
    document.getElementById('filtroAnioPlan').addEventListener('change', filtrarPlanMaterias);

    function filtrarPlanMaterias() {
        const grado = document.getElementById('filtroGradoPlan').value;
        const area = document.getElementById('filtroAreaPlan').value;
        // grupo y año de momento solo informativos
        const filas = document.querySelectorAll('#tablaPlanMaterias tbody tr');

        filas.forEach(row => {
            const rowGrado = row.cells[0].textContent.trim();
            const rowArea  = row.cells[3].textContent.trim();

            const matchGrado = !grado || rowGrado === grado + '°';
            const matchArea  = !area  || rowArea === area;

            row.style.display = (matchGrado && matchArea) ? '' : 'none';
        });
    }
</script>
@endsection

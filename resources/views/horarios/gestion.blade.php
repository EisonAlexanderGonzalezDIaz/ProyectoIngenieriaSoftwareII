{{-- resources/views/horarios/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    /* ==== ESTILOS PARA LOS TABS DE HORARIOS ==== */
    .card-header .nav-tabs {
        border-bottom: none;
    }

    .card-header .nav-tabs .nav-link {
        background-color: #f8f9fa;   /* gris muy claro */
        color: #0d6efd;              /* azul */
        border-radius: 0.5rem 0.5rem 0 0;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,0.7);
        margin-right: 0.25rem;
    }

    .card-header .nav-tabs .nav-link:hover {
        background-color: #e9ecef;
        color: #0a58ca;
    }

    .card-header .nav-tabs .nav-link.active {
        background-color: #ffffff;   /* blanco para destacar */
        color: #0d6efd;
        border-color: #ffffff #ffffff #0d6efd; 
        font-weight: 600;
        box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
    }
</style>

<div class="container py-4">

    {{-- =========================
         GESTIÓN DE HORARIOS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white rounded-top">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Gestión de Horarios
                </h4>
                <div>
                    {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a> 
                    <button class="btn btn-light btn-sm me-2" onclick="toggleAddHorarioForm()">
                        <i class="fas fa-plus"></i> Agregar Horario
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="exportHorarios()">
                        <i class="fas fa-file-export"></i> Exportar
                    </button>
                </div>
            </div>

            {{-- Tabs para separar Estudiantes y Docentes --}}
            <ul class="nav nav-tabs mt-3" id="horariosTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-estudiantes" type="button" role="tab"
                        aria-controls="tab-estudiantes" aria-selected="true">
                        <i class="fas fa-user-graduate me-1"></i> Horarios de Estudiantes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="docentes-tab" data-bs-toggle="tab"
                        data-bs-target="#tab-docentes" type="button" role="tab"
                        aria-controls="tab-docentes" aria-selected="false">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Horarios de Docentes
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body bg-light">
            <div class="tab-content" id="horariosTabContent">

                {{-- =========================
                     TAB: HORARIOS ESTUDIANTES
                ========================== --}}
                <div class="tab-pane fade show active" id="tab-estudiantes" role="tabpanel" aria-labelledby="estudiantes-tab">
                    
                    {{-- Barra de búsqueda --}}
                    <div class="input-group mb-3 mt-3">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchHorariosEst" class="form-control"
                            placeholder="Buscar horarios por grado, sección o materia...">
                    </div>

                    {{-- Filtros --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterGradoEst">
                                <option value="">Filtrar por Grado...</option>
                                <option value="9">9°</option>
                                <option value="10">10°</option>
                                <option value="11">11°</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterSeccionEst">
                                <option value="">Filtrar por Sección...</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterMateriaEst">
                                <option value="">Filtrar por Materia...</option>
                                <option value="Matemáticas">Matemáticas</option>
                                <option value="Español">Español</option>
                                <option value="Inglés">Inglés</option>
                                <option value="Ciencias">Ciencias</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tabla de horarios estudiantes --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Grado</th>
                                    <th>Sección</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Materia</th>
                                    <th>Docente</th>
                                    <th>Aula</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes">
                                <tr>
                                    <td>HOR001</td>
                                    <td>10°</td>
                                    <td>A</td>
                                    <td>07:00</td>
                                    <td>08:30</td>
                                    <td>Matemáticas</td>
                                    <td>Prof. García</td>
                                    <td>101</td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('HOR001')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteHorario('HOR001')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('HOR001')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>HOR002</td>
                                    <td>9°</td>
                                    <td>B</td>
                                    <td>08:45</td>
                                    <td>10:15</td>
                                    <td>Español</td>
                                    <td>Prof. López</td>
                                    <td>102</td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('HOR002')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteHorario('HOR002')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('HOR002')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>HOR003</td>
                                    <td>11°</td>
                                    <td>C</td>
                                    <td>10:30</td>
                                    <td>12:00</td>
                                    <td>Inglés</td>
                                    <td>Prof. Martínez</td>
                                    <td>103</td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('HOR003')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteHorario('HOR003')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('HOR003')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>HOR004</td>
                                    <td>10°</td>
                                    <td>A</td>
                                    <td>12:45</td>
                                    <td>14:15</td>
                                    <td>Ciencias</td>
                                    <td>Prof. García</td>
                                    <td>104</td>
                                    <td><span class="badge bg-secondary">Inactivo</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('HOR004')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteHorario('HOR004')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('HOR004')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación estudiantes --}}
                    <nav aria-label="Paginación horarios estudiantes">
                        <ul class="pagination justify-content-center mt-3">
                            <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>

                {{-- =========================
                     TAB: HORARIOS DOCENTES
                ========================== --}}
                <div class="tab-pane fade" id="tab-docentes" role="tabpanel" aria-labelledby="docentes-tab">

                    {{-- Barra de búsqueda --}}
                    <div class="input-group mb-3 mt-3">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchHorariosDoc" class="form-control"
                            placeholder="Buscar horarios por docente, materia o grado...">
                    </div>

                    {{-- Filtros --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterDocenteDoc">
                                <option value="">Filtrar por Docente...</option>
                                <option value="García">Prof. García</option>
                                <option value="López">Prof. López</option>
                                <option value="Martínez">Prof. Martínez</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterMateriaDoc">
                                <option value="">Filtrar por Materia...</option>
                                <option value="Matemáticas">Matemáticas</option>
                                <option value="Español">Español</option>
                                <option value="Inglés">Inglés</option>
                                <option value="Ciencias">Ciencias</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filterDiaDoc">
                                <option value="">Filtrar por Día...</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tabla de horarios docentes --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Docente</th>
                                    <th>Día</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Materia</th>
                                    <th>Grado</th>
                                    <th>Sección</th>
                                    <th>Aula</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDocentes">
                                <tr>
                                    <td>DOC001</td>
                                    <td>Prof. García</td>
                                    <td>Lunes</td>
                                    <td>07:00</td>
                                    <td>08:30</td>
                                    <td>Matemáticas</td>
                                    <td>10°</td>
                                    <td>A</td>
                                    <td>101</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('DOC001')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('DOC001')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DOC002</td>
                                    <td>Prof. López</td>
                                    <td>Martes</td>
                                    <td>08:45</td>
                                    <td>10:15</td>
                                    <td>Español</td>
                                    <td>9°</td>
                                    <td>B</td>
                                    <td>102</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('DOC002')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('DOC002')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DOC003</td>
                                    <td>Prof. Martínez</td>
                                    <td>Miércoles</td>
                                    <td>10:30</td>
                                    <td>12:00</td>
                                    <td>Inglés</td>
                                    <td>11°</td>
                                    <td>C</td>
                                    <td>103</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editHorario('DOC003')" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewHorario('DOC003')" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación docentes --}}
                    <nav aria-label="Paginación horarios docentes">
                        <ul class="pagination justify-content-center mt-3">
                            <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            {{-- ===========================================
                 FORMULARIO PARA AGREGAR NUEVO HORARIO
            ============================================ --}}
            <div id="addHorarioForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus text-primary me-2"></i>Agregar Nuevo Horario</h5>
                    <button type="button" class="btn-close" onclick="toggleAddHorarioForm()"></button>
                </div>
                <div class="card-body">
                    <form id="horarioForm">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="9">9°</option>
                                    <option value="10">10°</option>
                                    <option value="11">11°</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sección <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Aula <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Ej: 101, 102..." required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Materia <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Matemáticas">Matemáticas</option>
                                    <option value="Español">Español</option>
                                    <option value="Inglés">Inglés</option>
                                    <option value="Ciencias Naturales">Ciencias Naturales</option>
                                    <option value="Sociales">Sociales</option>
                                    <option value="Educación Física">Educación Física</option>
                                    <option value="Artística">Artística</option>
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

                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select">
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="2" placeholder="Notas adicionales del horario..."></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="saveHorario()">
                                <i class="fas fa-save me-2"></i>Guardar Horario
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAddHorarioForm()">
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
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    // Mostrar/Ocultar el formulario
    function toggleAddHorarioForm() {
        const form = document.getElementById('addHorarioForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // Simulación de guardado
    function saveHorario() {
        alert('✅ Horario guardado (simulación). Luego se conectará al backend.');
        toggleAddHorarioForm();
        document.getElementById('horarioForm').reset();
    }

    // Simulación de edición
    function editHorario(id) {
        alert('✏️ Editando horario: ' + id);
    }

    // Simulación de eliminación
    function deleteHorario(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
            alert('🗑️ Horario eliminado: ' + id);
        }
    }

    // Simulación de ver detalles
    function viewHorario(id) {
        alert('👁️ Viendo detalles del horario: ' + id);
    }

    // Simulación de exportar
    function exportHorarios() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // ==== BÚSQUEDA Y FILTROS ESTUDIANTES ====
    document.getElementById('searchHorariosEst').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tbodyEstudiantes tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    document.getElementById('filterGradoEst').addEventListener('change', filterEstudiantes);
    document.getElementById('filterSeccionEst').addEventListener('change', filterEstudiantes);
    document.getElementById('filterMateriaEst').addEventListener('change', filterEstudiantes);

    function filterEstudiantes() {
        const grado = document.getElementById('filterGradoEst').value;
        const seccion = document.getElementById('filterSeccionEst').value;
        const materia = document.getElementById('filterMateriaEst').value;
        const tableRows = document.querySelectorAll('#tbodyEstudiantes tr');

        tableRows.forEach(row => {
            const rowGrado = row.cells[1].textContent.trim();
            const rowSeccion = row.cells[2].textContent.trim();
            const rowMateria = row.cells[5].textContent.trim();

            const gradoMatch = !grado || rowGrado === grado + '°';
            const seccionMatch = !seccion || rowSeccion === seccion;
            const materiaMatch = !materia || rowMateria === materia;

            row.style.display = (gradoMatch && seccionMatch && materiaMatch) ? '' : 'none';
        });
    }

    // ==== BÚSQUEDA Y FILTROS DOCENTES ====
    document.getElementById('searchHorariosDoc').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tbodyDocentes tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    document.getElementById('filterDocenteDoc').addEventListener('change', filterDocentes);
    document.getElementById('filterMateriaDoc').addEventListener('change', filterDocentes);
    document.getElementById('filterDiaDoc').addEventListener('change', filterDocentes);

    function filterDocentes() {
        const docente = document.getElementById('filterDocenteDoc').value;
        const materia = document.getElementById('filterMateriaDoc').value;
        const dia = document.getElementById('filterDiaDoc').value;
        const tableRows = document.querySelectorAll('#tbodyDocentes tr');

        tableRows.forEach(row => {
            const rowDocente = row.cells[1].textContent.trim();
            const rowDia = row.cells[2].textContent.trim();
            const rowMateria = row.cells[5].textContent.trim();

            const docenteMatch = !docente || rowDocente.includes(docente);
            const diaMatch = !dia || rowDia === dia;
            const materiaMatch = !materia || rowMateria === materia;

            row.style.display = (docenteMatch && diaMatch && materiaMatch) ? '' : 'none';
        });
    }
</script>
@endsection

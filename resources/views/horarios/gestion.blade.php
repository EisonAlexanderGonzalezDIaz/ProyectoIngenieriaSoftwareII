{{-- resources/views/horarios/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE HORARIOS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Gestión de Horarios</h4>
            <div>
                <button class="btn btn-dark btn-sm me-2" onclick="toggleAddHorarioForm()">
                    <i class="fas fa-plus"></i> Agregar Horario
                </button>
                <button class="btn btn-outline-dark btn-sm" onclick="exportHorarios()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchHorarios" class="form-control" placeholder="Buscar horarios por grado, sección o docente...">
            </div>

            {{-- Filtros --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterGrado">
                        <option value="">Filtrar por Grado...</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                        <option value="11">11°</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterSeccion">
                        <option value="">Filtrar por Sección...</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="filterDocente">
                        <option value="">Filtrar por Docente...</option>
                        <option value="1">Prof. García</option>
                        <option value="2">Prof. López</option>
                        <option value="3">Prof. Martínez</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de horarios --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                    <thead class="table-warning">
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
                    <tbody>
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

            {{-- Paginación --}}
            <nav aria-label="Paginación horarios">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA AGREGAR NUEVO HORARIO
            ============================================ --}}
            <div id="addHorarioForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus text-warning me-2"></i>Agregar Nuevo Horario</h5>
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
                                    <option value="1">Matemáticas</option>
                                    <option value="2">Español</option>
                                    <option value="3">Inglés</option>
                                    <option value="4">Ciencias Naturales</option>
                                    <option value="5">Sociales</option>
                                    <option value="6">Educación Física</option>
                                    <option value="7">Artística</option>
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
                            <button type="button" class="btn btn-warning" onclick="saveHorario()">
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
        // Aquí se cargará el formulario con los datos del horario
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

    // Búsqueda en tiempo real
    document.getElementById('searchHorarios').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Filtros
    document.getElementById('filterGrado').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterSeccion').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterDocente').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const grado = document.getElementById('filterGrado').value;
        const seccion = document.getElementById('filterSeccion').value;
        const docente = document.getElementById('filterDocente').value;
        const tableRows = document.querySelectorAll('table tbody tr');

        tableRows.forEach(row => {
            const rowGrado = row.cells[1].textContent.trim();
            const rowSeccion = row.cells[2].textContent.trim();
            const rowDocente = row.cells[6].textContent.trim();

            const gradoMatch = !grado || rowGrado === grado + '°';
            const seccionMatch = !seccion || rowSeccion === seccion;
            const docenteMatch = !docente || rowDocente.includes(docente);

            row.style.display = (gradoMatch && seccionMatch && docenteMatch) ? '' : 'none';
        });
    }
</script>
@endsection
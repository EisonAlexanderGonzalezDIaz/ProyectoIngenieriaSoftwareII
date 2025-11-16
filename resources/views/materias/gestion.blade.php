{{-- resources/views/materias/gestion.blade.php --}} 
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE MATERIAS
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-book me-2"></i>Gestión de Materias</h4>
            <div>
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>    
                <button class="btn btn-light btn-sm me-2" onclick="toggleAddMateriaForm()">
                    <i class="fas fa-plus"></i> Agregar Materia
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="exportMaterias()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchMaterias" class="form-control" placeholder="Buscar materias por código, nombre o docente...">
            </div>

            {{-- Filtros --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <select class="form-select form-select-sm" id="filterGrado">
                        <option value="">Filtrar por Grado...</option>
                        <option value="6">6°</option>
                        <option value="7">7°</option>
                        <option value="8">8°</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                        <option value="11">11°</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select form-select-sm" id="filterEstado">
                        <option value="">Filtrar por Estado...</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de materias --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                    <thead class="table-primary">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Grado</th>
                            <th>Créditos</th>
                            <th>Horas Semanales</th>
                            <th>Docente</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MAT001</td>
                            <td>Matemáticas</td>
                            <td>10°</td>
                            <td>4</td>
                            <td>4</td>
                            <td>Prof. García</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editMateria('MAT001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria('MAT001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewMateria('MAT001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>ESP001</td>
                            <td>Español</td>
                            <td>9°</td>
                            <td>3</td>
                            <td>3</td>
                            <td>Prof. López</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editMateria('ESP001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria('ESP001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewMateria('ESP001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>ING001</td>
                            <td>Inglés</td>
                            <td>11°</td>
                            <td>3</td>
                            <td>3</td>
                            <td>Prof. Martínez</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editMateria('ING001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria('ING001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewMateria('ING001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>CIE001</td>
                            <td>Ciencias Naturales</td>
                            <td>10°</td>
                            <td>4</td>
                            <td>4</td>
                            <td>Prof. Rodríguez</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editMateria('CIE001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria('CIE001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewMateria('CIE001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>SOC001</td>
                            <td>Sociales</td>
                            <td>9°</td>
                            <td>3</td>
                            <td>3</td>
                            <td>Prof. Pérez</td>
                            <td><span class="badge bg-secondary">Inactivo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editMateria('SOC001')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria('SOC001')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewMateria('SOC001')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <nav aria-label="Paginación materias">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA AGREGAR NUEVA MATERIA
            ============================================ --}}
            <div id="addMateriaForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Agregar Nueva Materia</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="toggleAddMateriaForm()"></button>
                </div>
                <div class="card-body bg-light">
                    <form id="materiaForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-secondary" placeholder="Ej: MAT001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-secondary" placeholder="Nombre de la materia" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Descripción</label>
                            <textarea class="form-control border-secondary" rows="2" placeholder="Descripción de la materia..."></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Créditos <span class="text-danger">*</span></label>
                                <input type="number" class="form-control border-secondary" min="1" placeholder="Ej: 3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Horas Semanales <span class="text-danger">*</span></label>
                                <input type="number" class="form-control border-secondary" min="1" placeholder="Ej: 3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Grado <span class="text-danger">*</span></label>
                                <select class="form-select border-secondary" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="6">6°</option>
                                    <option value="7">7°</option>
                                    <option value="8">8°</option>
                                    <option value="9">9°</option>
                                    <option value="10">10°</option>
                                    <option value="11">11°</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Docente <span class="text-danger">*</span></label>
                            <select class="form-select border-secondary" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Prof. García</option>
                                <option value="2">Prof. López</option>
                                <option value="3">Prof. Martínez</option>
                                <option value="4">Prof. Rodríguez</option>
                                <option value="5">Prof. Pérez</option>
                                <option value="6">Prof. Suárez</option>
                                <option value="7">Prof. Castillo</option>
                                <option value="8">Prof. Torres</option>
                                <option value="9">Prof. Herrera</option>
                                <option value="10">Prof. Rojas</option>
                                <option value="11">Prof. Medina</option>
                                <option value="12">Prof. Vargas</option>
                                <option value="13">Prof. Jiménez</option>
                                <option value="14">Prof. Rivera</option>
                                <option value="15">Prof. Delgado</option>
                                <option value="16">Prof. Castaño</option>
                                <option value="17">Prof. León</option>
                                <option value="18">Prof. Rubio</option>
                                <option value="19">Prof. Gonzalez</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Estado</label>
                            <select class="form-select border-secondary">
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="saveMateria()">
                                <i class="fas fa-save me-2"></i>Guardar Materia
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAddMateriaForm()">
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
    function toggleAddMateriaForm() {
        const form = document.getElementById('addMateriaForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // Simulación de guardado
    function saveMateria() {
        alert('✅ Materia guardada (simulación). Luego se conectará al backend.');
        toggleAddMateriaForm();
        document.getElementById('materiaForm').reset();
    }

    // Simulación de edición
    function editMateria(id) {
        alert('✏️ Editando materia: ' + id);
        // Aquí se cargará el formulario con los datos de la materia
    }

    // Simulación de eliminación
    function deleteMateria(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta materia?')) {
            alert('🗑️ Materia eliminada: ' + id);
        }
    }

    // Simulación de ver detalles
    function viewMateria(id) {
        alert('👁️ Viendo detalles de la materia: ' + id);
    }

    // Simulación de exportar
    function exportMaterias() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // Búsqueda en tiempo real
    document.getElementById('searchMaterias').addEventListener('keyup', function(e) {
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

    document.getElementById('filterEstado').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const grado = document.getElementById('filterGrado').value;
        const estado = document.getElementById('filterEstado').value;
        const tableRows = document.querySelectorAll('table tbody tr');

        tableRows.forEach(row => {
            const rowGrado = row.cells[2].textContent.trim();
            const rowEstado = row.cells[6].textContent.trim();

            const gradoMatch = !grado || rowGrado === grado + '°';
            const estadoMatch = !estado || rowEstado.includes(estado);

            row.style.display = (gradoMatch && estadoMatch) ? '' : 'none';
        });
    }
</script>
@endsection

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
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded" id="materiasTable">
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
                        @if($materias->isEmpty())
                            <tr>
                                <td colspan="8" class="text-muted py-4">No hay materias registradas.</td>
                            </tr>
                        @else
                            @foreach($materias as $materia)
                                <tr data-id="{{ $materia->id }}" data-codigo="{{ $materia->codigo }}" 
                                    data-nombre="{{ $materia->nombre }}" data-grado="{{ $materia->grado }}"
                                    data-creditos="{{ $materia->creditos }}" data-horas="{{ $materia->horas_semanales }}"
                                    data-docente="{{ $materia->docente }}" data-descripcion="{{ $materia->descripcion ?? '' }}"
                                    data-estado="{{ $materia->estado }}">
                                    <td>{{ $materia->codigo }}</td>
                                    <td>{{ $materia->nombre }}</td>
                                    <td>{{ $materia->grado }}</td>
                                    <td>{{ $materia->creditos }}</td>
                                    <td>{{ $materia->horas_semanales }}</td>
                                    <td>{{ $materia->docente }}</td>
                                    <td>
                                        @if($materia->estado === 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="editMateria(this)" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteMateria(this)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewMateria(this)" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i><span id="formTitle">Agregar Nueva Materia</span></h5>
                    <button type="button" class="btn-close btn-close-white" onclick="resetForm()"></button>
                </div>
                <div class="card-body bg-light">
                    <form id="materiaForm">
                        @csrf
                        <input type="hidden" name="materia_id" id="materiaId" value="">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary">Código <span class="text-danger">*</span></label>
                                <input type="text" name="codigo" id="codigo" class="form-control border-secondary" placeholder="Ej: MAT001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control border-secondary" placeholder="Nombre de la materia" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control border-secondary" rows="2" placeholder="Descripción de la materia..."></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Créditos <span class="text-danger">*</span></label>
                                <input type="number" name="creditos" id="creditos" class="form-control border-secondary" min="1" placeholder="Ej: 3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Horas Semanales <span class="text-danger">*</span></label>
                                <input type="number" name="horas_semanales" id="horas_semanales" class="form-control border-secondary" min="1" placeholder="Ej: 3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary">Grado <span class="text-danger">*</span></label>
                                <select name="grado" id="grado" class="form-select border-secondary" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="6°">6°</option>
                                    <option value="7°">7°</option>
                                    <option value="8°">8°</option>
                                    <option value="9°">9°</option>
                                    <option value="10°">10°</option>
                                    <option value="11°">11°</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Docente <span class="text-danger">*</span></label>
                            <input type="text" name="docente" id="docente" class="form-control border-secondary" placeholder="Nombre del docente" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary">Estado</label>
                            <select name="estado" id="estado" class="form-select border-secondary">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i><span id="submitBtnText">Guardar Materia</span>
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Mostrar/Ocultar el formulario
    function toggleAddMateriaForm() {
        const form = document.getElementById('addMateriaForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        if (form.style.display === 'block') {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }

    // Resetear formulario
    function resetForm() {
        document.getElementById('materiaForm').reset();
        document.getElementById('materiaId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('formTitle').textContent = 'Agregar Nueva Materia';
        document.getElementById('submitBtnText').textContent = 'Guardar Materia';
        document.getElementById('codigo').readOnly = false;
        toggleAddMateriaForm();
    }

    // Editar materia
    function editMateria(button) {
        const row = button.closest('tr');
        const id = row.dataset.id;
        const codigo = row.dataset.codigo;
        const nombre = row.dataset.nombre;
        const descripcion = row.dataset.descripcion;
        const creditos = row.dataset.creditos;
        const horas = row.dataset.horas;
        const docente = row.dataset.docente;
        const grado = row.dataset.grado;
        const estado = row.dataset.estado;

        // Llenar formulario con datos
        document.getElementById('materiaId').value = id;
        document.getElementById('codigo').value = codigo;
        document.getElementById('nombre').value = nombre;
        document.getElementById('descripcion').value = descripcion;
        document.getElementById('creditos').value = creditos;
        document.getElementById('horas_semanales').value = horas;
        document.getElementById('docente').value = docente;
        document.getElementById('grado').value = grado;
        document.getElementById('estado').value = estado;

        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('formTitle').textContent = 'Editar Materia';
        document.getElementById('submitBtnText').textContent = 'Actualizar Materia';
        document.getElementById('codigo').readOnly = true; // Evitar cambio de código único

        toggleAddMateriaForm();
    }

    // Eliminar materia
    function deleteMateria(button) {
        const row = button.closest('tr');
        const id = row.dataset.id;
        const nombre = row.dataset.nombre;

        if (confirm(`¿Estás seguro de que deseas eliminar la materia "${nombre}"?`)) {
            fetch(`/materias/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    location.reload(); // Recargar página
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Ver detalles
    function viewMateria(button) {
        const row = button.closest('tr');
        const nombre = row.dataset.nombre;
        const codigo = row.dataset.codigo;
        const descripcion = row.dataset.descripcion;
        const docente = row.dataset.docente;
        const grado = row.dataset.grado;

        alert(`📋 Detalles de la Materia:\n\nCódigo: ${codigo}\nNombre: ${nombre}\nDocente: ${docente}\nGrado: ${grado}\nDescripción: ${descripcion || 'N/A'}`);
    }

    // Simulación de exportar
    function exportMaterias() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // Manejar envío del formulario
    document.getElementById('materiaForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const materiaId = document.getElementById('materiaId').value;
        const method = document.getElementById('formMethod').value;
        const url = materiaId && method === 'PUT' ? `/materias/${materiaId}` : '/materias/store';

        const formData = {
            codigo: document.getElementById('codigo').value,
            nombre: document.getElementById('nombre').value,
            descripcion: document.getElementById('descripcion').value || null,
            creditos: parseInt(document.getElementById('creditos').value),
            horas_semanales: parseInt(document.getElementById('horas_semanales').value),
            docente: document.getElementById('docente').value,
            grado: document.getElementById('grado').value,
            estado: document.getElementById('estado').value,
        };

        const fetchOptions = {
            method: method === 'PUT' ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        };

        try {
            const response = await fetch(url, fetchOptions);
            const data = await response.json();

            if (data.message) {
                alert(data.message);
                location.reload(); // Recargar página
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error al guardar la materia.');
        }
    });

    // Búsqueda en tiempo real
    document.getElementById('searchMaterias').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#materiasTable tbody tr');
        
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
        const tableRows = document.querySelectorAll('#materiasTable tbody tr');

        tableRows.forEach(row => {
            const rowGrado = row.dataset.grado;
            const rowEstado = row.dataset.estado;

            const gradoMatch = !grado || rowGrado === grado;
            const estadoMatch = !estado || rowEstado === estado;

            row.style.display = (gradoMatch && estadoMatch) ? '' : 'none';
        });
    }
</script>
@endsection

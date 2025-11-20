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

                    {{-- Tabla de horarios de estudiantes --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center bg-white shadow-sm rounded" id="horariosTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Grado</th>
                                    <th>Sección</th>
                                    <th>Día</th>
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
                                @forelse($horarios ?? collect() as $horario)
                                    <tr data-id="{{ $horario->id }}"
                                        data-grado="{{ $horario->grado }}"
                                        data-seccion="{{ $horario->seccion }}"
                                        data-dia="{{ $horario->dia }}"
                                        data-hora_inicio="{{ $horario->hora_inicio }}"
                                        data-hora_fin="{{ $horario->hora_fin }}"
                                        data-materia="{{ $horario->materia_nombre }}"
                                        data-docente="{{ $horario->docente_nombre }}"
                                        data-aula="{{ $horario->aula }}"
                                        data-estado="{{ $horario->estado }}">
                                        <td>{{ $horario->id }}</td>
                                        <td>{{ $horario->grado }}</td>
                                        <td>{{ $horario->seccion }}</td>
                                        <td>{{ $horario->dia }}</td>
                                        <td>{{ $horario->hora_inicio }}</td>
                                        <td>{{ $horario->hora_fin }}</td>
                                        <td>{{ $horario->materia_nombre }}</td>
                                        <td>{{ $horario->docente_nombre }}</td>
                                        <td>{{ $horario->aula }}</td>
                                        <td>
                                            @if($horario->estado === 'Activo')
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" onclick="editHorario(this)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteHorario(this)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="viewHorario(this)" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-muted py-4">No hay horarios registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                            placeholder="Buscar horarios por docente, materia o día...">
                    </div>

                    {{-- Tabla de horarios por docente (usamos el mismo $horarios) --}}
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
                                @foreach($horarios ?? collect() as $horario)
                                    <tr>
                                        <td>{{ $horario->id }}</td>
                                        <td>{{ $horario->docente_nombre }}</td>
                                        <td>{{ $horario->dia }}</td>
                                        <td>{{ $horario->hora_inicio }}</td>
                                        <td>{{ $horario->hora_fin }}</td>
                                        <td>{{ $horario->materia_nombre }}</td>
                                        <td>{{ $horario->grado }}</td>
                                        <td>{{ $horario->seccion }}</td>
                                        <td>{{ $horario->aula }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" onclick="editHorario(this)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="viewHorario(this)" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- ============================================
                 FORMULARIO PARA AGREGAR / EDITAR HORARIO
            ============================================ --}}
            <div id="addHorarioForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-plus text-primary me-2"></i>
                        Agregar / Editar Horario
                    </h5>
                    <button type="button" class="btn-close" onclick="toggleAddHorarioForm()"></button>
                </div>
                <div class="card-body">
                    <form id="horarioForm">
                        @csrf
                        <input type="hidden" name="horario_id" id="horarioId" value="">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                <select name="grado" id="grado" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="9°">9°</option>
                                    <option value="10°">10°</option>
                                    <option value="11°">11°</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sección <span class="text-danger">*</span></label>
                                <select name="seccion" id="seccion" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Día <span class="text-danger">*</span></label>
                                <select name="dia" id="dia" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miércoles">Miércoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Aula <span class="text-danger">*</span></label>
                                <input type="text" name="aula" id="aula" class="form-control" placeholder="Ej: 101" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Materia <span class="text-danger">*</span></label>
                                <input type="text" name="materia_nombre" id="materia_nombre" class="form-control" placeholder="Nombre de la materia" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Docente <span class="text-danger">*</span></label>
                                <input type="text" name="docente_nombre" id="docente_nombre" class="form-control" placeholder="Nombre del docente" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select">
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitBtnText">Guardar Horario</span>
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div> {{-- card-body --}}
    </div> {{-- card --}}
</div> {{-- container --}}

{{-- =====================================
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Mostrar/Ocultar formulario
    function toggleAddHorarioForm() {
        const form = document.getElementById('addHorarioForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        } else {
            form.style.display = 'none';
        }
    }

    function resetForm() {
        document.getElementById('horarioForm').reset();
        document.getElementById('horarioId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('submitBtnText').textContent = 'Guardar Horario';
        toggleAddHorarioForm();
    }

    function editHorario(button) {
        const row = button.closest('tr');
        if (!row) return;

        document.getElementById('horarioId').value = row.dataset.id || '';
        document.getElementById('grado').value = row.dataset.grado || '';
        document.getElementById('seccion').value = row.dataset.seccion || '';
        document.getElementById('dia').value = row.dataset.dia || '';
        document.getElementById('hora_inicio').value = row.dataset.hora_inicio || '';
        document.getElementById('hora_fin').value = row.dataset.hora_fin || '';
        document.getElementById('materia_nombre').value = row.dataset.materia || row.dataset.materia_nombre || '';
        document.getElementById('docente_nombre').value = row.dataset.docente || row.dataset.docente_nombre || '';
        document.getElementById('aula').value = row.dataset.aula || '';
        document.getElementById('estado').value = row.dataset.estado || 'Activo';

        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('submitBtnText').textContent = 'Actualizar Horario';

        const form = document.getElementById('addHorarioForm');
        form.style.display = 'block';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    async function deleteHorario(button) {
        const row = button.closest('tr');
        if (!row) return;

        const id = row.dataset.id;
        const grado = row.dataset.grado || '';
        const seccion = row.dataset.seccion || '';

        if (!confirm(`¿Eliminar horario de ${grado} ${seccion}?`)) return;

        try {
            const res = await fetch(`/horarios/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.message) {
                alert(data.message);
                location.reload();
            }
        } catch (err) {
            console.error(err);
            alert('Error al eliminar el horario.');
        }
    }

    function viewHorario(button) {
        const row = button.closest('tr');
        if (!row) return;

        const grado = row.dataset.grado || '';
        const seccion = row.dataset.seccion || '';
        const dia = row.dataset.dia || row.cells[2]?.textContent || '';
        const hi = row.dataset.hora_inicio || row.cells[3]?.textContent || '';
        const hf = row.dataset.hora_fin || row.cells[4]?.textContent || '';
        const materia = row.dataset.materia || row.cells[5]?.textContent || '';
        const docente = row.dataset.docente || row.cells[1]?.textContent || '';
        const aula = row.dataset.aula || row.cells[8]?.textContent || '';

        alert(
            `Detalles del horario:\n` +
            `Grado: ${grado}\n` +
            `Sección: ${seccion}\n` +
            `Día: ${dia}\n` +
            `Hora: ${hi} - ${hf}\n` +
            `Materia: ${materia}\n` +
            `Docente: ${docente}\n` +
            `Aula: ${aula}`
        );
    }

    function exportHorarios() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }

    // Envío del formulario (crear/actualizar)
    document.getElementById('horarioForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const horarioId = document.getElementById('horarioId').value;
        const method = document.getElementById('formMethod').value;

        const url = (horarioId && method === 'PUT')
            ? `/horarios/${horarioId}`
            : '/horarios/store';

        const formData = {
            grado: document.getElementById('grado').value,
            seccion: document.getElementById('seccion').value,
            dia: document.getElementById('dia').value,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_fin: document.getElementById('hora_fin').value,
            aula: document.getElementById('aula').value,
            materia_nombre: document.getElementById('materia_nombre').value,
            docente_nombre: document.getElementById('docente_nombre').value,
            estado: document.getElementById('estado').value,
        };

        try {
            const res = await fetch(url, {
                method: (method === 'PUT') ? 'PUT' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            const data = await res.json();
            if (data.message) {
                alert(data.message);
                location.reload();
            }
        } catch (err) {
            console.error(err);
            alert('Error al guardar el horario.');
        }
    });

    // ==== BÚSQUEDA ESTUDIANTES ====
    document.getElementById('searchHorariosEst')?.addEventListener('keyup', function(e){
        const v = e.target.value.toLowerCase();
        document.querySelectorAll('#tbodyEstudiantes tr').forEach(r => {
            r.style.display = r.textContent.toLowerCase().includes(v) ? '' : 'none';
        });
    });

    // ==== BÚSQUEDA DOCENTES ====
    document.getElementById('searchHorariosDoc')?.addEventListener('keyup', function(e){
        const v = e.target.value.toLowerCase();
        document.querySelectorAll('#tbodyDocentes tr').forEach(r => {
            r.style.display = r.textContent.toLowerCase().includes(v) ? '' : 'none';
        });
    });
</script>
@endsection

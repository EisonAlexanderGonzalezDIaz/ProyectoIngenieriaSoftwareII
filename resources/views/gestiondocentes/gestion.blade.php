{{-- resources/views/gestiondocentes/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    /* Paleta azul/gris para encabezado y botones */
    .page-header {
        background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13,110,253,0.04);
    }

    .badge-estado-activo { background-color: #198754; color: #fff; }
    .badge-estado-inactivo { background-color: #6c757d; color: #fff; }
</style>

<div class="container py-4">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header page-header rounded-top d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2"></i>Gestionar Docentes
            </h4>

            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>
                <button type="button" class="btn btn-light btn-sm me-2" onclick="toggleAddDocenteForm()">
                    <i class="fas fa-plus"></i> Nuevo docente
                </button>
                <button type="button" class="btn btn-outline-light btn-sm" onclick="exportDocentes()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Barra de búsqueda y filtros --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input id="searchDocentes" type="text" class="form-control" placeholder="Buscar por nombre, materia o email...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <select id="filterEstadoDocente" class="form-select form-select-sm w-auto d-inline-block">
                        <option value="">Todos los estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de docentes --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Materia</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDocentes">
                        @foreach($docentes as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td class="text-start ps-4">{{ $d->nombre }}</td>
                            <td>{{ $d->materia ?? '-' }}</td>
                            <td>{{ $d->email ?? '-' }}</td>
                            <td>{{ $d->telefono ?? '-' }}</td>
                            <td>
                                @if(strtolower($d->estado) === 'activo')
                                    <span class="badge badge-estado-activo">Activo</span>
                                @else
                                    <span class="badge badge-estado-inactivo">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button
                                        type="button"
                                        class="btn btn-outline-info btn-sm"
                                        data-id="{{ $d->id }}"
                                        onclick="verDocente(this)"
                                        title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        data-id="{{ $d->id }}"
                                        onclick="editarDocente(this)"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        data-id="{{ $d->id }}"
                                        onclick="eliminarDocente(this)"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación (maqueta) --}}
            <nav aria-label="Paginación docentes" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- FORMULARIO PARA AGREGAR / EDITAR DOCENTE (MAQUETA) --}}
                <div id="addDocenteForm" class="card shadow-sm border-0 mt-4" style="display:none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus text-primary me-2"></i>Nuevo Docente</h5>
                    <button type="button" class="btn-close" onclick="toggleAddDocenteForm()"></button>
                </div>
                <div class="card-body">
                    <form id="formDocente" action="{{ route('gestiondocentes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="docente_id" id="docenteIdInput" value="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input name="nombre" type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Materias (selección múltiple)</label>
                                <select name="materias[]" id="selectMaterias" class="form-select" multiple>
                                    @foreach($materias as $m)
                                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mantén presionada la tecla Ctrl/Cmd para seleccionar varias.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input name="telefono" type="tel" class="form-control">
                            </div>
                        </div>

                            <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAddDocenteForm()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPTS (solo front) --}}
<script>
    function toggleAddDocenteForm() {
        const el = document.getElementById('addDocenteForm');
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
        if (el.style.display === 'block') {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }

    const csrf = '{{ csrf_token() }}';

    function guardarDocenteSimulado() {
        // Se mantiene para compatibilidad; el formulario ahora hace POST real.
        document.getElementById('formDocente').submit();
    }

    function exportDocentes() {
        alert('📁 Exportación simulada de docentes.');
    }

    // Ahora las funciones reciben el botón y sacan el id de data-id
    function verDocente(btn) {
        const id = btn.dataset.id;
        alert('👁️ Ver docente ID: ' + id + ' (simulación)');
    }

    function editarDocente(btn) {
        const id = btn.dataset.id;
        // Rellenar el formulario con los datos de la fila
        const row = btn.closest('tr');
        const nombre = row.querySelector('td:nth-child(2)').textContent.trim();
        const materia = row.querySelector('td:nth-child(3)').textContent.trim();
        const email = row.querySelector('td:nth-child(4)').textContent.trim();
        const telefono = row.querySelector('td:nth-child(5)').textContent.trim();

        document.querySelector('#formDocente input[name="nombre"]').value = nombre;
        // rellenar select multiple de materias (si existe)
        const select = document.getElementById('selectMaterias');
        if (select) {
            // limpiar selección
            Array.from(select.options).forEach(o => o.selected = false);
            if (materia && materia !== '-') {
                const parts = materia.split(',').map(s => s.trim());
                parts.forEach(p => {
                    const opt = Array.from(select.options).find(o => o.value.toLowerCase() === p.toLowerCase());
                    if (opt) opt.selected = true;
                });
            }
        }
        document.querySelector('#formDocente input[name="email"]').value = (email === '-') ? '' : email;
        document.querySelector('#formDocente input[name="telefono"]').value = (telefono === '-') ? '' : telefono;
        document.getElementById('docenteIdInput').value = id;

        // Cambiar acción del formulario para actualizar
        document.getElementById('formDocente').action = '/gestiondocentes/' + id;
        document.getElementById('formMethod').value = 'PUT';

        // Mostrar formulario
        const el = document.getElementById('addDocenteForm');
        el.style.display = 'block';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function eliminarDocente(btn) {
        const id = btn.dataset.id;
        if (confirm('¿Eliminar docente ID: ' + id + '?')) {
            fetch('/gestiondocentes/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(r => r.json()).then(res => {
                if (res && res.ok) {
                    alert('🗑️ Docente eliminado');
                    window.location.reload();
                } else {
                    alert('Error al eliminar docente');
                }
            }).catch(err => {
                console.error(err);
                alert('Error al eliminar docente');
            });
        }
    }

    // Búsqueda
    document.getElementById('searchDocentes').addEventListener('keyup', function(e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#tablaDocentes tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });

    // Filtro por estado
    document.getElementById('filterEstadoDocente').addEventListener('change', function(e) {
        const val = e.target.value;
        document.querySelectorAll('#tablaDocentes tr').forEach(row => {
            if (!val) {
                row.style.display = '';
                return;
            }
            const estado = row.querySelector('td:nth-child(6)').textContent.trim();
            row.style.display = (estado === val) ? '' : 'none';
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Registrar Asistencia - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Registrar Asistencia</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Curso</label>
                            <select id="cursoSelect" class="form-control" required>
                                <option value="">Seleccionar curso...</option>
                                @foreach($cursos as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Materia</label>
                            <select id="materiaSelect" class="form-control" required>
                                <option value="">Seleccionar materia...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" id="fechaSelect" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-secondary" onclick="cargarEstudiantes()">Cargar Estudiantes</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Estudiantes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Observaciones</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="estudiantesBody">
                            <tr><td colspan="5" class="text-center text-muted">Selecciona curso, materia y fecha.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-success" onclick="guardarTodo()">Guardar Todo</button>
            </div>
        </div>
    </div>
</div>

<script>
// Poblar materias según curso
document.getElementById('cursoSelect').addEventListener('change', function() {
    const cursoId = this.value;
    const materiaSelect = document.getElementById('materiaSelect');
    materiaSelect.innerHTML = '<option value="">Seleccionar materia...</option>';
    
    if (!cursoId) return;
    
    fetch(`/api/docente/materias-por-curso?curso_id=${cursoId}`)
        .then(r => r.json())
        .then(data => {
            (data.materias || []).forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                materiaSelect.appendChild(opt);
            });
        });
});

// Establecer fecha de hoy por defecto
document.getElementById('fechaSelect').valueAsDate = new Date();

function cargarEstudiantes() {
    const cursoId = document.getElementById('cursoSelect').value;
    const materiaId = document.getElementById('materiaSelect').value;

    if (!cursoId || !materiaId) {
        alert('Selecciona curso y materia');
        return;
    }

    fetch(`{{ route('docente.obtener_estudiantes_por_curso') }}?curso_id=${cursoId}&materia_id=${materiaId}`)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('estudiantesBody');
            const estudiantes = data.estudiantes || [];

            if (estudiantes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay estudiantes.</td></tr>';
                return;
            }

            let html = '';
            estudiantes.forEach(e => {
                html += `<tr data-est="${e.id}">
                    <td>${e.name}</td>
                    <td>${e.email}</td>
                    <td>
                        <select class="form-control form-control-sm estado-select" required>
                            <option value="presente">Presente</option>
                            <option value="ausente">Ausente</option>
                            <option value="justificado">Justificado</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm obs-input" placeholder="Observaciones..."></td>
                    <td><button class="btn btn-sm btn-info" onclick="guardarAsistenciaUno(${e.id})">Guardar</button></td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

function guardarAsistenciaUno(estudianteId) {
    const fila = document.querySelector(`[data-est="${estudianteId}"]`);
    const materiaId = document.getElementById('materiaSelect').value;
    const fecha = document.getElementById('fechaSelect').value;
    const estado = fila.querySelector('.estado-select').value;
    const observaciones = fila.querySelector('.obs-input').value;

    fetch('{{ route('docente.guardar_asistencia') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            estudiante_id: estudianteId,
            materia_id: materiaId,
            fecha: fecha,
            estado: estado,
            observaciones: observaciones
        })
    })
    .then(r => r.json())
    .then(json => {
        if (json.success) {
            fila.style.backgroundColor = '#d4edda';
            alert('Asistencia guardada');
        } else {
            alert('Error: ' + json.error);
        }
    });
}

function guardarTodo() {
    const filas = document.querySelectorAll('#estudiantesBody tr');
    let count = 0;

    filas.forEach(fila => {
        const estudianteId = fila.getAttribute('data-est');
        if (estudianteId) {
            guardarAsistenciaUno(estudianteId);
            count++;
        }
    });

    alert(`Guardando asistencia de ${count} estudiantes...`);
}
</script>
@endsection

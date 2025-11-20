@extends('layouts.app')

@section('title', 'Generar Informe del Curso - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-9 mx-auto">
            <h1 class="mb-4">Generar Informe del Curso</h1>

            <div class="card">
                <div class="card-body">
                    <form id="informeForm" method="POST" action="{{ route('docente.generar_informe_post') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Curso</label>
                                <select id="cursoSelect" name="curso_id" class="form-control" required>
                                    <option value="">Seleccionar curso...</option>
                                    @foreach($cursos as $c)
                                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Materia</label>
                                <select id="materiaSelect" name="subject_id" class="form-control" required onchange="cargarDatos()">
                                    <option value="">Seleccionar materia...</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Período</label>
                            <input type="text" name="periodo" class="form-control" placeholder="ej: 2025-I" required>
                        </div>

                        <hr>
                        <h5 class="mb-3">Información Estadística (Auto-completado)</h5>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Estudiantes Total</h6>
                                        <h4 id="totalEstudiantes">—</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Promedio General</h6>
                                        <h4 id="promedioGeneral">—</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Aprobados</h6>
                                        <h4 id="aprobados" class="text-success">—</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Reprobados</h6>
                                        <h4 id="reprobados" class="text-danger">—</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Desempeño General</label>
                            <textarea name="desempeno_general" class="form-control" rows="3" required placeholder="Describe el desempeño general del curso..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fortalezas</label>
                            <textarea name="fortalezas" class="form-control" rows="3" required placeholder="Fortalezas observadas en los estudiantes..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Debilidades</label>
                            <textarea name="debilidades" class="form-control" rows="3" required placeholder="Debilidades y áreas de mejora..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Recomendaciones</label>
                            <textarea name="recomendaciones" class="form-control" rows="3" required placeholder="Recomendaciones para el próximo período..."></textarea>
                        </div>

                        <button class="btn btn-primary btn-lg">Generar Informe</button>
                    </form>

                    <div id="mensaje" class="mt-3"></div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informes Generados</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Curso</th>
                                <th>Materia</th>
                                <th>Período</th>
                                <th>Fecha</th>
                                <th>Promedio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="informesBody">
                            <tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
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

function cargarDatos() {
    const cursoId = document.getElementById('cursoSelect').value;
    const materiaId = document.getElementById('materiaSelect').value;

    if (!cursoId || !materiaId) return;

    fetch(`{{ route('docente.obtener_datos_autorellenado') }}?curso_id=${cursoId}&subject_id=${materiaId}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('totalEstudiantes').textContent = data.estudiantes_total || 0;
            document.getElementById('promedioGeneral').textContent = (data.promedio_general || 0).toFixed(2);
            document.getElementById('aprobados').textContent = data.estudiantes_aprobados || 0;
            document.getElementById('reprobados').textContent = data.estudiantes_reprobados || 0;
        });
}

function cargarInformes() {
    fetch('/api/docente/informes')
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('informesBody');
            const informes = data.informes || [];

            if (informes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay informes generados.</td></tr>';
                return;
            }

            let html = '';
            informes.forEach(i => {
                html += `<tr>
                    <td>${i.curso_nombre || '—'}</td>
                    <td>${i.materia_nombre || '—'}</td>
                    <td>${i.periodo}</td>
                    <td>${new Date(i.fecha_generacion).toLocaleDateString()}</td>
                    <td>${(i.promedio_curso || 0).toFixed(2)}</td>
                    <td><a href="#" class="btn btn-sm btn-outline-primary">Ver</a></td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

document.getElementById('informeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(this.action, { method: 'POST', body: formData, headers: {'X-CSRF-TOKEN': token } })
        .then(r => r.json())
        .then(json => {
            const msg = document.getElementById('mensaje');
            if (json.success) {
                msg.innerHTML = '<div class="alert alert-success">Informe generado correctamente.</div>';
                cargarInformes();
            } else {
                msg.innerHTML = '<div class="alert alert-danger">Error: ' + (json.error || 'No se generó el informe') + '</div>';
            }
        });
});

cargarInformes();
</script>
@endsection

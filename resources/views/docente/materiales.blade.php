@extends('layouts.app')

@section('title', 'Subir Material Académico - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">Subir Material Académico</h1>

            <div class="card">
                <div class="card-body">
                    <form id="materialForm" enctype="multipart/form-data" method="POST" action="{{ route('docente.subir_material') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Materia</label>
                            <select name="materia_id" class="form-control" required>
                                <option value="">Seleccionar materia...</option>
                                @foreach($materias as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Título del material</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción (opcional)</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de material</label>
                            <select name="tipo" class="form-control" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="documento">Documento</option>
                                <option value="video">Video</option>
                                <option value="presentacion">Presentación</option>
                                <option value="tarea">Tarea</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Archivo (máximo 50MB)</label>
                            <input type="file" name="archivo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de vencimiento (opcional)</label>
                            <input type="date" name="fecha_vencimiento" class="form-control">
                        </div>

                        <button class="btn btn-primary">Subir Material</button>
                    </form>

                    <div id="mensaje" class="mt-3"></div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Materiales Subidos</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Materia</th>
                                <th>Tipo</th>
                                <th>Fecha Publicación</th>
                                <th>Vencimiento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="materialesBody">
                            <tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cargarMateriales() {
    fetch('/api/docente/materiales')
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('materialesBody');
            const materiales = data.materiales || [];

            if (materiales.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay materiales subidos.</td></tr>';
                return;
            }

            let html = '';
            materiales.forEach(m => {
                html += `<tr>
                    <td><strong>${m.titulo}</strong></td>
                    <td>${m.materia_nombre || '—'}</td>
                    <td><span class="badge bg-info">${m.tipo}</span></td>
                    <td>${new Date(m.fecha_publicacion).toLocaleDateString()}</td>
                    <td>${m.fecha_vencimiento ? new Date(m.fecha_vencimiento).toLocaleDateString() : '—'}</td>
                    <td>
                        <a href="/storage/${m.archivo_url}" class="btn btn-sm btn-outline-primary" download>Descargar</a>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

document.getElementById('materialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(this.action, { method: 'POST', body: formData, headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value } })
        .then(r => r.json())
        .then(json => {
            const msg = document.getElementById('mensaje');
            if (json.success) {
                msg.innerHTML = '<div class="alert alert-success">Material subido correctamente.</div>';
                this.reset();
                cargarMateriales();
            } else {
                msg.innerHTML = '<div class="alert alert-danger">Error: ' + (json.error || 'No se subió el material') + '</div>';
            }
        });
});

cargarMateriales();
</script>
@endsection

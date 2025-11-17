@extends('layouts.app')

@section('title', 'Tareas y Entregas')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mis Tareas Académicas</h1>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tareas Pendientes y Entregas</h5>
                </div>
                <div class="card-body">
                    <div id="tareasTable">
                        <p class="text-muted text-center">Cargando tareas...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para entregar tarea -->
<div class="modal fade" id="entregarModal" tabindex="-1" role="dialog" aria-labelledby="entregarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entregarModalLabel">Entregar Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="entregarForm">
                    @csrf
                    <input type="hidden" id="tarea_id" name="tarea_id">
                    <div class="form-group">
                        <label for="archivo" class="form-label">Seleccionar Archivo *</label>
                        <input type="file" id="archivo" name="archivo" class="form-control" required>
                        <small class="form-text text-muted">Máximo 10 MB</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submitBtn">Entregar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarTareas();

    document.getElementById('submitBtn').addEventListener('click', function() {
        entregarTarea();
    });
});

function cargarTareas() {
    fetch('{{ route("estudiante.obtener_tareas") }}')
        .then(response => response.json())
        .then(data => {
            if (data.tareas && data.tareas.length > 0) {
                renderizarTabla(data.tareas);
            } else {
                document.getElementById('tareasTable').innerHTML = `
                    <div class="alert alert-info">No hay tareas disponibles.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tareasTable').innerHTML = `
                <div class="alert alert-danger">Error al cargar las tareas.</div>
            `;
        });
}

function renderizarTabla(tareas) {
    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Materia</th><th>Título</th><th>Descripción</th><th>Fecha Entrega</th><th>Acciones</th></tr></thead>';
    html += '<tbody>';

    tareas.forEach(tarea => {
        const materia = tarea.materia ? tarea.materia.nombre : 'N/A';
        const docente = tarea.docente ? tarea.docente.name : 'N/A';
        const fechaEntrega = new Date(tarea.fecha_entrega).toLocaleDateString('es-ES');
        
        html += `<tr>
            <td>${materia}</td>
            <td><strong>${tarea.titulo}</strong></td>
            <td>${tarea.descripcion.substring(0, 50)}...</td>
            <td>${fechaEntrega}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="mostrarEntregarModal(${tarea.id}, '${tarea.titulo}')">
                    <i class="fas fa-upload"></i> Entregar
                </button>
            </td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById('tareasTable').innerHTML = html;
}

function mostrarEntregarModal(tareaId, titulo) {
    document.getElementById('tarea_id').value = tareaId;
    document.getElementById('entregarModalLabel').textContent = 'Entregar: ' + titulo;
    $('#entregarModal').modal('show');
}

function entregarTarea() {
    const form = document.getElementById('entregarForm');
    const formData = new FormData(form);

    fetch('{{ route("estudiante.entregar_tarea") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.entrega) {
            alert('Tarea entregada exitosamente');
            $('#entregarModal').modal('hide');
            form.reset();
            cargarTareas();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al entregar la tarea');
    });
}
</script>
@endsection

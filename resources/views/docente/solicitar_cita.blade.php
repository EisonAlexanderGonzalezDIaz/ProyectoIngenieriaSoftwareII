@extends('layouts.app')

@section('title', 'Solicitar Cita - Docente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">Solicitar Cita a Orientación</h1>

            <div class="card">
                <div class="card-body">
                    <form id="citaForm" method="POST" action="{{ route('docente.crear_cita') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Estudiante</label>
                            <input type="text" name="estudiante_nombre" class="form-control" placeholder="Buscar estudiante..." id="estudianteInput">
                            <input type="hidden" name="estudiante_id" id="estudianteId" required>
                            <div id="estudiantesList" class="list-group mt-2" style="display: none;"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo de la cita</label>
                            <textarea name="motivo" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha sugerida (opcional)</label>
                            <input type="date" name="fecha_sugerida" class="form-control">
                        </div>

                        <button class="btn btn-primary">Solicitar Cita</button>
                    </form>

                    <div id="mensaje" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Buscar estudiantes
document.getElementById('estudianteInput').addEventListener('keyup', function() {
    const query = this.value;
    if (query.length < 2) {
        document.getElementById('estudiantesList').style.display = 'none';
        return;
    }

    fetch(`/api/estudiantes/buscar?q=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(data => {
            const lista = document.getElementById('estudiantesList');
            lista.innerHTML = '';
            (data.estudiantes || []).forEach(e => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = e.name;
                item.onclick = (ev) => {
                    ev.preventDefault();
                    document.getElementById('estudianteInput').value = e.name;
                    document.getElementById('estudianteId').value = e.id;
                    lista.style.display = 'none';
                };
                lista.appendChild(item);
            });
            lista.style.display = lista.children.length > 0 ? 'block' : 'none';
        });
});

document.getElementById('citaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(this.action, { method: 'POST', body: formData, headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value } })
        .then(r => r.json())
        .then(json => {
            const msg = document.getElementById('mensaje');
            if (json.success) {
                msg.innerHTML = '<div class="alert alert-success">Cita solicitada correctamente.</div>';
                this.reset();
            } else {
                msg.innerHTML = '<div class="alert alert-danger">Error: ' + (json.error || 'Ocurrió un error') + '</div>';
            }
        });
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Notificaciones - Acudiente')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Notificaciones</h1>
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Centro de Notificaciones</h5>
                    <button class="btn btn-sm btn-light" id="marcarTodas">Marcar todas como leídas</button>
                </div>
                <div class="card-body">
                    <div id="notificacionesList"><p class="text-muted">Cargando notificaciones...</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let pagina = 1;

function cargar() {
    const url = new URL('{{ route('acudiente.obtener_notificaciones') }}', window.location.origin);
    url.searchParams.append('page', pagina);
    fetch(url)
        .then(r => r.json())
        .then(data => {
            const items = data.notificaciones.data || [];
            if (items.length === 0 && pagina === 1) {
                document.getElementById('notificacionesList').innerHTML = '<div class="alert alert-info">No tienes notificaciones.</div>';
                return;
            }
            if (pagina === 1) render(items); else append(items);
        });
}

function render(items) {
    let html = '';
    items.forEach(n => {
        html += `<div class="card mb-2 ${n.leida ? 'text-muted' : 'fw-bold'}">
            <div class="card-body">
                <h6>${n.titulo}</h6>
                <p>${n.descripcion}</p>
                <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
                ${!n.leida ? `<div class="mt-2"><button class="btn btn-sm btn-primary" onclick="marcar(${n.id})">Marcar leída</button></div>` : ''}
            </div>
        </div>`;
    });
    document.getElementById('notificacionesList').innerHTML = html;
}

function append(items) {
    const container = document.getElementById('notificacionesList');
    let html = container.innerHTML;
    items.forEach(n => {
        html += `<div class="card mb-2 ${n.leida ? 'text-muted' : 'fw-bold'}">
            <div class="card-body">
                <h6>${n.titulo}</h6>
                <p>${n.descripcion}</p>
                <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
            </div>
        </div>`;
    });
    container.innerHTML = html;
}

function marcar(id) {
    fetch(`{{ route('acudiente.marcar_notificacion_leida', '') }}/${id}`, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }})
        .then(r => r.json()).then(() => cargar());
}

document.getElementById('marcarTodas').addEventListener('click', function() {
    document.querySelectorAll('button[onclick^="marcar("]').forEach(btn => btn.click());
});

cargar();
</script>
@endsection

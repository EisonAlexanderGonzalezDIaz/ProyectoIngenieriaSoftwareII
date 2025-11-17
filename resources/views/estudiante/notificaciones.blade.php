@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h1 class="mb-4">Mis Notificaciones</h1>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Centro de Notificaciones</h5>
                        <button class="btn btn-sm btn-light" id="marcarTodas">Marcar todas como leídas</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notificacionesList">
                        <p class="text-muted text-center">Cargando notificaciones...</p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-outline-primary" id="cargarMas">Cargar más</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let paginaActual = 1;

document.addEventListener('DOMContentLoaded', function() {
    cargarNotificaciones();

    document.getElementById('marcarTodas').addEventListener('click', function() {
        marcarTodasLecidas();
    });

    document.getElementById('cargarMas').addEventListener('click', function() {
        paginaActual++;
        cargarNotificaciones(paginaActual);
    });
});

function cargarNotificaciones(pagina = 1) {
    const url = new URL('{{ route("estudiante.obtener_notificaciones") }}', window.location.origin);
    url.searchParams.append('page', pagina);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.notificaciones && data.notificaciones.data && data.notificaciones.data.length > 0) {
                if (pagina === 1) {
                    renderizarNotificaciones(data.notificaciones.data);
                } else {
                    agregarNotificaciones(data.notificaciones.data);
                }

                if (pagina >= data.notificaciones.last_page) {
                    document.getElementById('cargarMas').disabled = true;
                    document.getElementById('cargarMas').textContent = 'No hay más notificaciones';
                }
            } else if (pagina === 1) {
                document.getElementById('notificacionesList').innerHTML = `
                    <div class="alert alert-info">No tienes notificaciones.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (pagina === 1) {
                document.getElementById('notificacionesList').innerHTML = `
                    <div class="alert alert-danger">Error al cargar las notificaciones.</div>
                `;
            }
        });
}

function renderizarNotificaciones(notificaciones) {
    let html = '';
    notificaciones.forEach(notif => {
        html += generarHtmlNotificacion(notif);
    });
    document.getElementById('notificacionesList').innerHTML = html;
}

function agregarNotificaciones(notificaciones) {
    let html = document.getElementById('notificacionesList').innerHTML;
    notificaciones.forEach(notif => {
        html += generarHtmlNotificacion(notif);
    });
    document.getElementById('notificacionesList').innerHTML = html;
}

function generarHtmlNotificacion(notif) {
    const fechaCreacion = new Date(notif.created_at).toLocaleDateString('es-ES');
    const tipoClass = {
        'evento': 'info',
        'noticia': 'primary',
        'recordatorio': 'warning',
        'cambio': 'danger'
    }[notif.tipo] || 'secondary';

    const leida = notif.leida ? 'text-muted' : 'fw-bold';

    return `
        <div class="card mb-2 ${leida}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h6 class="card-title">${notif.titulo}</h6>
                        <p class="card-text">${notif.descripcion}</p>
                        <small class="text-muted">
                            <span class="badge bg-${tipoClass}">${notif.tipo}</span>
                            ${fechaCreacion}
                        </small>
                    </div>
                    <div class="col-md-2 text-end">
                        ${!notif.leida ? `
                            <button class="btn btn-sm btn-primary" onclick="marcarNotificacionLeida(${notif.id})">
                                <i class="fas fa-check"></i> Marcar leída
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function marcarNotificacionLeida(notificacionId) {
    fetch(`{{ route('estudiante.marcar_notificacion_leida', '') }}/${notificacionId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.notificacion) {
            cargarNotificaciones(1);
        }
    })
    .catch(error => console.error('Error:', error));
}

function marcarTodasLecidas() {
    const notificaciones = document.querySelectorAll('.card');
    let promesas = [];

    document.querySelectorAll('button[onclick*="marcarNotificacionLeida"]').forEach(btn => {
        const match = btn.onclick.toString().match(/\((\d+)\)/);
        if (match) {
            const notificacionId = match[1];
            promesas.push(
                fetch(`{{ route('estudiante.marcar_notificacion_leida', '') }}/${notificacionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                        'Accept': 'application/json'
                    }
                })
            );
        }
    });

    Promise.all(promesas).then(() => {
        alert('Todas las notificaciones han sido marcadas como leídas');
        cargarNotificaciones(1);
    }).catch(error => {
        console.error('Error:', error);
        alert('Error al marcar las notificaciones');
    });
}
</script>
@endsection

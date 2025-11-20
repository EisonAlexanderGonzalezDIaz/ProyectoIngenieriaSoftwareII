@extends('layouts.app')

@section('title', 'Solicitar Certificación')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">Solicitar Certificación</h1>

            <!-- Formulario para solicitar certificación -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Nueva Solicitud de Certificación</h5>
                </div>
                <div class="card-body">
                    <form id="certificacionForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tipo" class="form-label">Tipo de Certificación *</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">-- Seleccionar Tipo --</option>
                                <option value="acta_calificaciones">Acta de Calificaciones</option>
                                <option value="certificado_conducta">Certificado de Conducta</option>
                                <option value="certificado_asistencia">Certificado de Asistencia</option>
                                <option value="diploma">Diploma</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Solicitar Certificación</button>
                    </form>
                </div>
            </div>

            <!-- Lista de certificaciones solicitadas -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Mis Certificaciones</h5>
                </div>
                <div class="card-body">
                    <div id="certificacionesList">
                        <p class="text-muted">Cargando certificaciones...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarCertificaciones();

    document.getElementById('certificacionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        crearCertificacion();
    });
});

function cargarCertificaciones() {
    fetch('{{ route("estudiante.obtener_certificaciones") }}')
        .then(response => response.json())
        .then(data => {
            if (data.certificaciones && data.certificaciones.length > 0) {
                renderizarTabla(data.certificaciones);
            } else {
                document.getElementById('certificacionesList').innerHTML = `
                    <div class="alert alert-info">No tienes certificaciones solicitadas.</div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function renderizarTabla(certificaciones) {
    let html = '<table class="table table-striped table-hover">';
    html += '<thead class="table-primary"><tr><th>Tipo</th><th>Estado</th><th>Fecha Solicitud</th><th>Acciones</th></tr></thead>';
    html += '<tbody>';

    certificaciones.forEach(cert => {
        const fechaSolicitud = new Date(cert.fecha_solicitud).toLocaleDateString('es-ES');
        const estadoBadgeClass = {
            'solicitado': 'warning',
            'procesando': 'info',
            'listo': 'success',
            'descargado': 'secondary'
        }[cert.estado] || 'secondary';

        let acciones = '';
        if (cert.estado === 'listo' || cert.estado === 'descargado') {
            acciones = `<a href="{{ route('estudiante.descargar_certificacion', '') }}/${cert.id}" class="btn btn-sm btn-success">
                <i class="fas fa-download"></i> Descargar
            </a>`;
        } else {
            acciones = '<span class="text-muted">Pendiente</span>';
        }

        html += `<tr>
            <td>${cert.tipo}</td>
            <td><span class="badge bg-${estadoBadgeClass}">${cert.estado}</span></td>
            <td>${fechaSolicitud}</td>
            <td>${acciones}</td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById('certificacionesList').innerHTML = html;
}

function crearCertificacion() {
    const formData = {
        tipo: document.getElementById('tipo').value,
        _token: document.querySelector('input[name="_token"]').value
    };

    fetch('{{ route("estudiante.crear_certificacion") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.certificacion) {
            alert('Certificación solicitada exitosamente');
            document.getElementById('certificacionForm').reset();
            cargarCertificaciones();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al solicitar la certificación');
    });
}
</script>
@endsection

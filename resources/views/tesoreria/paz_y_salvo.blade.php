@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-check-double me-2 text-success"></i>Paz y Salvo
            </h1>
            <p class="text-muted">Genere certificados de paz y salvo para acudientes sin deudas pendientes</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Generar Paz y Salvo</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formPazSalvo" action="{{ route('tesoreria.view.pazysalvo') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccione el Acudiente <span class="text-danger">*</span></label>
                            <input type="text" id="searchAcudiente" class="form-control" placeholder="Buscar acudiente por nombre o email...">
                            <small class="text-muted">Escriba para buscar entre los acudientes registrados</small>
                        </div>

                        <div id="acudienteList" class="list-group mb-3" style="max-height: 300px; overflow-y: auto; display: none;">
                            {{-- La lista se completará dinámicamente con AJAX --}}
                        </div>

                        <input type="hidden" id="acudienteId" name="acudiente_id">

                        <div class="alert alert-info d-none" id="alertInfo" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="alertMessage"></span>
                        </div>

                        <div class="alert alert-success d-none" id="alertSuccess" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Paz y salvo generado exitosamente
                        </div>

                        <button type="button" class="btn btn-success w-100 mt-3" id="btnGenerar">
                            <i class="fas fa-certificate me-2"></i>Generar Paz y Salvo
                        </button>
                    </form>
                </div>
            </div>

            {{-- Resultado del paz y salvo --}}
            <div class="card border-0 shadow-sm mt-4 d-none" id="resultCard">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Certificado de Paz y Salvo</h5>
                </div>
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-certificate fa-4x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-2" id="resultName"></h4>
                    <p class="text-muted mb-3">Fecha: <span id="resultFecha"></span></p>
                    <div class="alert alert-light border p-3 my-3">
                        <p class="mb-0 fw-bold">
                            <i class="fas fa-check text-success me-2"></i>Este acudiente está al día con todas sus obligaciones financieras.
                        </p>
                    </div>
                    <p class="text-muted small">Token: <code id="resultToken"></code></p>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <button class="btn btn-secondary ms-2" onclick="location.reload()">
                        <i class="fas fa-redo me-2"></i>Generar otro
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>¿Qué es Paz y Salvo?</strong>
                        <p class="small text-muted mt-2">Un paz y salvo es un certificado que confirma que el acudiente no tiene deudas pendientes con el colegio.</p>
                    </div>
                    <div class="alert alert-info small mb-0">
                        <strong>Requisitos:</strong>
                        <ul class="mb-0 mt-2">
                            <li>El acudiente debe estar registrado</li>
                            <li>No debe haber deudas pendientes</li>
                            <li>Todos los pagos deben estar al día</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // AJAX para buscar acudientes
    document.getElementById('searchAcudiente').addEventListener('keyup', async function() {
        const search = this.value;
        if (search.length < 2) {
            document.getElementById('acudienteList').style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/acudientes/search?q=${search}`);
            const data = await response.json();
            
            const list = document.getElementById('acudienteList');
            list.innerHTML = '';
            
            if (data.length === 0) {
                list.innerHTML = '<div class="list-group-item">No se encontraron acudientes</div>';
            } else {
                data.forEach(acudiente => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<strong>${acudiente.name}</strong><br><small class="text-muted">${acudiente.email}</small>`;
                    item.onclick = (e) => {
                        e.preventDefault();
                        document.getElementById('acudienteId').value = acudiente.id;
                        document.getElementById('searchAcudiente').value = acudiente.name;
                        list.style.display = 'none';
                    };
                    list.appendChild(item);
                });
            }
            list.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Generar paz y salvo
    document.getElementById('btnGenerar').addEventListener('click', async function() {
        const acudienteId = document.getElementById('acudienteId').value;
        if (!acudienteId) {
            alert('Por favor seleccione un acudiente');
            return;
        }

        try {
            const response = await fetch(`/tesoreria/paz-y-salvo/${acudienteId}`);
            const data = await response.json();

            if (data.paz_y_salvo === false) {
                document.getElementById('alertMessage').textContent = `Deuda pendiente: $${data.monto_pendiente.toFixed(2)}`;
                document.getElementById('alertInfo').classList.remove('d-none');
                document.getElementById('resultCard').classList.add('d-none');
            } else {
                document.getElementById('resultName').textContent = data.paz_y_salvo.nombre;
                document.getElementById('resultFecha').textContent = new Date(data.paz_y_salvo.fecha).toLocaleDateString();
                document.getElementById('resultToken').textContent = data.paz_y_salvo.token;
                document.getElementById('alertSuccess').classList.remove('d-none');
                document.getElementById('resultCard').classList.remove('d-none');
                document.getElementById('alertInfo').classList.add('d-none');
            }
        } catch (error) {
            alert('Error al generar paz y salvo: ' + error.message);
        }
    });
</script>
@endsection

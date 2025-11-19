@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-wallet me-2 text-danger"></i>Cartera
            </h1>
            <p class="text-muted">Consulte el estado de la cartera y pagos pendientes por acudiente</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cartera de Cobranza</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="searchCartera" class="form-control" placeholder="Buscar por acudiente...">
                        </div>
                        <div class="col-md-3">
                            <select id="filterRiesgo" class="form-select">
                                <option value="">Ordenar por...</option>
                                <option value="monto_desc">Mayor deuda primero</option>
                                <option value="monto_asc">Menor deuda primero</option>
                                <option value="nombre">Nombre (A-Z)</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-end">
                            <button class="btn btn-danger" onclick="exportarCartera()">
                                <i class="fas fa-download me-2"></i>Exportar Cartera
                            </button>
                        </div>
                    </div>

                    {{-- Resumen rápido --}}
                    <div class="row mb-4">
                        <div class="col-md-3 mb-2">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase small">Total Cartera</h6>
                                <h3 class="text-danger fw-bold">$<span id="totalCartera">0.00</span></h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase small">Acudientes en Deuda</h6>
                                <h3 class="fw-bold"><span id="countAcudientes">0</span></h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase small">Deuda Promedio</h6>
                                <h3 class="fw-bold">$<span id="deudaPromedio">0.00</span></h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted text-uppercase small">Deuda Máxima</h6>
                                <h3 class="text-danger fw-bold">$<span id="deudaMaxima">0.00</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de cartera --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Acudiente</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Deuda Pendiente</th>
                                    <th>% del Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="carteraTable">
                                <tr><td colspan="6" class="text-center text-muted py-4">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function cargarCartera() {
        try {
            const response = await fetch('{{ route("tesoreria.cartera") }}');
            const data = await response.json();
            
            const table = document.getElementById('carteraTable');
            table.innerHTML = '';
            
            if (data.cartera.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay deudas pendientes</td></tr>';
                document.getElementById('totalCartera').textContent = '0.00';
                return;
            }

            let total = 0;
            data.cartera.forEach(deuda => {
                total += parseFloat(deuda.total_pendiente);
            });

            document.getElementById('totalCartera').textContent = total.toFixed(2);
            document.getElementById('countAcudientes').textContent = data.cartera.length;
            document.getElementById('deudaPromedio').textContent = (total / data.cartera.length).toFixed(2);
            document.getElementById('deudaMaxima').textContent = Math.max(...data.cartera.map(d => parseFloat(d.total_pendiente))).toFixed(2);

            data.cartera.forEach(deuda => {
                const porciento = ((parseFloat(deuda.total_pendiente) / total) * 100).toFixed(1);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${deuda.acudiente?.name || 'N/A'}</strong></td>
                    <td>${deuda.acudiente?.email || '-'}</td>
                    <td>${deuda.acudiente?.phone || '-'}</td>
                    <td>
                        <span class="badge bg-danger">$${parseFloat(deuda.total_pendiente).toFixed(2)}</span>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: ${porciento}%">${porciento}%</div>
                        </div>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary" onclick="verDetalles(${deuda.acudiente?.id})">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tesoreria.view.pagos') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </td>
                `;
                table.appendChild(row);
            });
        } catch (error) {
            console.error('Error al cargar cartera:', error);
        }
    }

    function exportarCartera() {
        alert('Exportar cartera a Excel (función en desarrollo)');
    }

    function verDetalles(acudienteId) {
        window.location.href = `{{ route('tesoreria.view.estado') }}?acudiente=${acudienteId}`;
    }

    cargarCartera();
</script>
@endsection

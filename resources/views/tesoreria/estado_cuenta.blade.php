@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-tie me-2 text-purple" style="color: #6f42c1;"></i>Estado de Cuenta
            </h1>
            <p class="text-muted">Consulte el estado de cuenta detallado de cada acudiente</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #6f42c1; color: white;" class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Consultar Estado de Cuenta</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Seleccione Acudiente <span class="text-danger">*</span></label>
                        <input type="text" id="searchAcudiente" class="form-control" placeholder="Buscar acudiente...">
                        <input type="hidden" id="acudienteId">
                    </div>

                    <div id="estadoContent" class="d-none">
                        {{-- Estado general --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="text-muted text-uppercase small">Saldo Actual</h6>
                                        <h3 class="fw-bold" id="saldoActual">$0.00</h3>
                                        <small id="estadoSaldo" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="text-muted text-uppercase small">Últimas 3 Transacciones</h6>
                                        <p id="ultimasTransacciones" class="small mb-0">Cargando...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de movimientos --}}
                        <h6 class="fw-bold mb-3">Historial de Movimientos</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody id="movimientosTable">
                                    <tr><td colspan="5" class="text-center text-muted py-3">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="sinResultado" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Seleccione un acudiente para ver su estado de cuenta</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Acudiente</h5>
                </div>
                <div class="card-body" id="infoAcudiente">
                    <p class="text-muted small">Seleccione un acudiente para ver información</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('tesoreria.view.pagos') }}" class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-plus me-2"></i>Registrar Pago
                    </a>
                    <a href="{{ route('tesoreria.view.becas') }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-graduation-cap me-2"></i>Aplicar Beca
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchAcudiente').addEventListener('keyup', async function() {
        const search = this.value;
        if (search.length < 2) return;

        try {
            const response = await fetch(`/api/acudientes/search?q=${search}`);
            const data = await response.json();
            
            // Mostrar lista y permitir selección
            console.log('Acudientes encontrados:', data);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    async function cargarEstadoCuenta(acudienteId) {
        try {
            const response = await fetch(`/tesoreria/estado-cuenta/${acudienteId}`);
            const data = await response.json();

            if (!data.acudiente) {
                alert('Acudiente no encontrado');
                return;
            }

            // Mostrar información general
            document.getElementById('infoAcudiente').innerHTML = `
                <div class="mb-2">
                    <strong>${data.acudiente.name}</strong><br>
                    <small class="text-muted">${data.acudiente.email}</small>
                </div>
            `;

            // Mostrar saldo
            const saldo = parseFloat(data.saldo);
            const estadoClass = saldo >= 0 ? 'text-success' : 'text-danger';
            const estadoTexto = saldo >= 0 ? 'Sin deuda' : 'Debe al colegio';
            
            document.getElementById('saldoActual').innerHTML = `<span class="${estadoClass}">$${Math.abs(saldo).toFixed(2)}</span>`;
            document.getElementById('estadoSaldo').textContent = estadoTexto;

            // Mostrar últimas transacciones
            const ultimas = data.pagos.slice(0, 3).map(p => `${p.tipo}: $${parseFloat(p.monto).toFixed(2)}`).join(', ');
            document.getElementById('ultimasTransacciones').textContent = ultimas || 'Sin movimientos';

            // Llenar tabla de movimientos
            const table = document.getElementById('movimientosTable');
            table.innerHTML = '';

            let saldoCorrernte = 0;
            data.pagos.forEach(pago => {
                saldoCorrernte += parseFloat(pago.monto);
                const row = document.createElement('tr');
                const monto = parseFloat(pago.monto);
                const montoClass = monto >= 0 ? 'text-success' : 'text-danger';
                const tipoColor = pago.tipo === 'pago' ? 'badge-success' : pago.tipo === 'beca' ? 'badge-info' : 'badge-warning';
                
                row.innerHTML = `
                    <td>${new Date(pago.created_at).toLocaleDateString()}</td>
                    <td>${pago.descripcion || pago.tipo}</td>
                    <td><span class="badge ${tipoColor}">${pago.tipo}</span></td>
                    <td class="${montoClass} fw-bold">$${monto.toFixed(2)}</td>
                    <td>$${saldoCorrernte.toFixed(2)}</td>
                `;
                table.appendChild(row);
            });

            document.getElementById('estadoContent').classList.remove('d-none');
            document.getElementById('sinResultado').classList.add('d-none');
        } catch (error) {
            console.error('Error al cargar estado de cuenta:', error);
            alert('Error al cargar la información');
        }
    }

    // Búsqueda con AJAX
    document.getElementById('searchAcudiente').addEventListener('change', async function() {
        const acudienteId = this.value;
        if (acudienteId) {
            await cargarEstadoCuenta(acudienteId);
        }
    });
</script>
@endsection

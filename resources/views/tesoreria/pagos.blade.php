@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-credit-card me-2 text-info"></i>Registrar Pagos
            </h1>
            <p class="text-muted">Registre pagos realizados por acudientes o instituciones</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nuevo Pago</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formPago">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Acudiente <span class="text-danger">*</span></label>
                                <input type="text" id="searchAcudiente" class="form-control" placeholder="Buscar acudiente..." required>
                                <input type="hidden" id="acudienteId" name="acudiente_id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Referencia de Pago (opcional)</label>
                                <input type="text" name="pago_id" class="form-control" placeholder="Ej: Comprobante #123">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Monto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="monto" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de Pago</label>
                                <select name="metodo" class="form-select">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia Bancaria</option>
                                    <option value="Tarjeta Débito">Tarjeta Débito</option>
                                    <option value="Tarjeta Crédito">Tarjeta Crédito</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalle del pago..."></textarea>
                        </div>

                        <div class="alert alert-success d-none" id="successAlert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Pago registrado exitosamente
                        </div>

                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-check me-2"></i>Registrar Pago
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabla de pagos recientes --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pagos Registrados Hoy</h5>
                    <select id="filterMetodo" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Todos los métodos</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hora</th>
                                <th>Acudiente</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="pagosTable">
                            <tr><td colspan="6" class="text-center text-muted py-4">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Métodos de Pago</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-2">
                        <strong>Efectivo</strong>
                        <p class="text-muted mb-0">Pago en dinero en caja</p>
                    </div>
                    <div class="mb-2">
                        <strong>Transferencia Bancaria</strong>
                        <p class="text-muted mb-0">Depósito o transferencia a cuenta escolar</p>
                    </div>
                    <div class="mb-2">
                        <strong>Tarjeta</strong>
                        <p class="text-muted mb-0">Débito o crédito (si aplica)</p>
                    </div>
                    <div>
                        <strong>Cheque</strong>
                        <p class="text-muted mb-0">Cheque a nombre de la institución</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resumen del Día</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <h6 class="text-muted text-uppercase small">Total Recaudado</h6>
                        <h3 class="text-info fw-bold">$<span id="totalDia">0.00</span></h3>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="text-muted text-uppercase small">Pagos Registrados</h6>
                        <h3 class="fw-bold"><span id="countPagos">0</span></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Buscar acudientes
    document.getElementById('searchAcudiente').addEventListener('keyup', async function() {
        const search = this.value;
        if (search.length < 2) return;

        try {
            const response = await fetch(`/api/acudientes/search?q=${search}`);
            const data = await response.json();
            console.log('Acudientes encontrados:', data);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Enviar formulario de pago
    document.getElementById('formPago').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const acudienteId = document.getElementById('acudienteId').value;
        if (!acudienteId) {
            alert('Por favor seleccione un acudiente');
            return;
        }

        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("tesoreria.pago.registrar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    pago_id: formData.get('pago_id') || null,
                    acudiente_id: formData.get('acudiente_id'),
                    monto: parseFloat(formData.get('monto')),
                    metodo: formData.get('metodo'),
                    descripcion: formData.get('descripcion'),
                }),
            });

            const data = await response.json();
            
            if (data.pago) {
                document.getElementById('successAlert').classList.remove('d-none');
                this.reset();
                document.getElementById('acudienteId').value = '';
                
                setTimeout(() => {
                    cargarPagos();
                    document.getElementById('successAlert').classList.add('d-none');
                }, 2000);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    // Cargar pagos
    async function cargarPagos() {
        try {
            const response = await fetch('/api/pagos?tipo=pago&estado=pagado');
            const data = await response.json();
            
            const table = document.getElementById('pagosTable');
            table.innerHTML = '';
            
            let total = 0;
            let count = 0;

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay pagos registrados</td></tr>';
            } else {
                data.forEach(pago => {
                    total += parseFloat(pago.monto);
                    count++;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(pago.created_at).toLocaleTimeString()}</td>
                        <td>${pago.acudiente?.name || 'N/A'}</td>
                        <td>$${parseFloat(pago.monto).toFixed(2)}</td>
                        <td>${pago.metodo || '-'}</td>
                        <td><span class="badge bg-success">${pago.estado}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="reversarPago(${pago.id})">
                                <i class="fas fa-undo"></i>
                            </button>
                        </td>
                    `;
                    table.appendChild(row);
                });
            }

            document.getElementById('totalDia').textContent = total.toFixed(2);
            document.getElementById('countPagos').textContent = count;
        } catch (error) {
            console.error('Error al cargar pagos:', error);
        }
    }

    // Reversión de pago
    async function reversarPago(pagoId) {
        if (!confirm('¿Desea revertir este pago?')) return;

        try {
            const response = await fetch('{{ route("tesoreria.devolucion") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    pago_id: pagoId,
                    motivo: 'Reverso de pago',
                }),
            });

            const data = await response.json();
            if (data.devuelven) {
                alert('Pago revertido exitosamente');
                cargarPagos();
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    // Cargar pagos al abrir la página
    cargarPagos();
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-undo-alt me-2 text-warning"></i>Devoluciones
            </h1>
            <p class="text-muted">Gestione y procese devoluciones de pagos registrados</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Procesar Devolución</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formDevolucion">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccione Pago a Devolver <span class="text-danger">*</span></label>
                            <input type="text" id="searchPago" class="form-control" placeholder="Buscar por acudiente o ID de pago..." required>
                            <input type="hidden" id="pagoId" name="pago_id">
                            
                            <div id="pagoList" class="list-group mt-2" style="max-height: 250px; overflow-y: auto; display: none;">
                                {{-- Se llena dinámicamente --}}
                            </div>
                        </div>

                        <div class="alert alert-info d-none" id="pagoInfo" role="alert">
                            <div><strong>Acudiente:</strong> <span id="infoacudiente"></span></div>
                            <div><strong>Monto Original:</strong> $<span id="infomonto"></span></div>
                            <div><strong>Fecha:</strong> <span id="infofecha"></span></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Motivo de la Devolución <span class="text-danger">*</span></label>
                            <select name="motivo" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Pago duplicado">Pago duplicado</option>
                                <option value="Cambio de decisión">Cambio de decisión</option>
                                <option value="Error en registro">Error en registro</option>
                                <option value="Servicio no prestado">Servicio no prestado</option>
                                <option value="Solicitud del acudiente">Solicitud del acudiente</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="alert alert-success d-none" id="successAlert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Devolución procesada exitosamente
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-check me-2"></i>Procesar Devolución
                        </button>
                    </form>
                </div>
            </div>

            {{-- Historial de devoluciones --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Devoluciones Procesadas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Acudiente</th>
                                <th>Monto</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="devolucionesTable">
                            <tr><td colspan="5" class="text-center text-muted py-4">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resumen de Devoluciones</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <h6 class="text-muted text-uppercase small">Total Devuelto Hoy</h6>
                        <h3 class="text-warning fw-bold">$<span id="totalDevuelto">0.00</span></h3>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="text-muted text-uppercase small">Cantidad de Devoluciones</h6>
                        <h3 class="fw-bold"><span id="countDevoluciones">0</span></h3>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Importante</h5>
                </div>
                <div class="card-body small">
                    <div class="alert alert-warning mb-0">
                        <strong>Procedimiento:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Busque el pago a devolver</li>
                            <li>Seleccione el motivo</li>
                            <li>Procese la devolución</li>
                            <li>Se registrará un movimiento negativo</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Buscar pagos para devolver
    document.getElementById('searchPago').addEventListener('keyup', async function() {
        const search = this.value;
        if (search.length < 2) {
            document.getElementById('pagoList').style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/pagos/search?q=${search}`);
            const data = await response.json();
            
            const list = document.getElementById('pagoList');
            list.innerHTML = '';
            
            if (data.length === 0) {
                list.innerHTML = '<div class="list-group-item">No se encontraron pagos</div>';
            } else {
                data.forEach(pago => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `
                        <strong>${pago.acudiente?.name || 'N/A'}</strong><br>
                        <small class="text-muted">Monto: $${parseFloat(pago.monto).toFixed(2)} - ${new Date(pago.created_at).toLocaleDateString()}</small>
                    `;
                    item.onclick = (e) => {
                        e.preventDefault();
                        document.getElementById('pagoId').value = pago.id;
                        document.getElementById('searchPago').value = `Pago #${pago.id}`;
                        document.getElementById('infoacudiente').textContent = pago.acudiente?.name || 'N/A';
                        document.getElementById('infomonto').textContent = parseFloat(pago.monto).toFixed(2);
                        document.getElementById('infofecha').textContent = new Date(pago.created_at).toLocaleDateString();
                        document.getElementById('pagoInfo').classList.remove('d-none');
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

    // Procesar devolución
    document.getElementById('formDevolucion').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const pagoId = document.getElementById('pagoId').value;
        if (!pagoId) {
            alert('Por favor seleccione un pago');
            return;
        }

        const formData = new FormData(this);
        
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
                    motivo: formData.get('motivo'),
                }),
            });

            const data = await response.json();
            
            if (data.devuelven) {
                document.getElementById('successAlert').classList.remove('d-none');
                this.reset();
                document.getElementById('pagoId').value = '';
                document.getElementById('pagoInfo').classList.add('d-none');
                
                setTimeout(() => {
                    cargarDevoluciones();
                    document.getElementById('successAlert').classList.add('d-none');
                }, 2000);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    // Cargar devoluciones
    async function cargarDevoluciones() {
        try {
            const response = await fetch('/api/pagos?tipo=devolucion');
            const data = await response.json();
            
            const table = document.getElementById('devolucionesTable');
            table.innerHTML = '';
            
            let total = 0;
            let count = 0;

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No hay devoluciones</td></tr>';
            } else {
                data.forEach(dev => {
                    total += Math.abs(parseFloat(dev.monto));
                    count++;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(dev.created_at).toLocaleDateString()}</td>
                        <td>${dev.acudiente?.name || 'N/A'}</td>
                        <td>$${Math.abs(parseFloat(dev.monto)).toFixed(2)}</td>
                        <td>${dev.descripcion || '-'}</td>
                        <td><span class="badge bg-success">${dev.estado}</span></td>
                    `;
                    table.appendChild(row);
                });
            }

            document.getElementById('totalDevuelto').textContent = total.toFixed(2);
            document.getElementById('countDevoluciones').textContent = count;
        } catch (error) {
            console.error('Error al cargar devoluciones:', error);
        }
    }

    cargarDevoluciones();
</script>
@endsection

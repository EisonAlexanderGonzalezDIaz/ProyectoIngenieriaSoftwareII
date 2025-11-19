@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-receipt me-2 text-primary"></i>Factura de Matrícula
            </h1>
            <p class="text-muted">Cree facturas de matrícula para nuevos estudiantes y registre los conceptos</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nueva Factura</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formFactura">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Acudiente <span class="text-danger">*</span></label>
                                <input type="text" id="searchAcudiente" class="form-control" placeholder="Buscar acudiente..." required>
                                <input type="hidden" id="acudienteId" name="acudiente_id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Matrícula (opcional)</label>
                                <select name="matricula_id" class="form-select">
                                    <option value="">-- Sin matrícula --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Monto Total <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="monto" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descripción</label>
                                <input type="text" name="descripcion" class="form-control" placeholder="Ej: Matrícula 2025">
                            </div>
                        </div>

                        <div class="alert alert-success d-none" id="successAlert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Factura creada exitosamente. ID: <strong id="facturaId"></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Crear Factura
                        </button>
                    </form>
                </div>
            </div>

            {{-- Historial de facturas recientes --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Facturas Recientes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Acudiente</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="facturasTable">
                            <tr><td colspan="5" class="text-center text-muted py-4">Cargando...</td></tr>
                        </tbody>
                    </table>
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
                        <strong>Componentes de Factura</strong>
                        <p class="small text-muted mt-2">Una factura puede incluir:</p>
                        <ul class="small text-muted">
                            <li>Matrícula anual</li>
                            <li>Cuotas mensuales</li>
                            <li>Servicios adicionales</li>
                            <li>Otros conceptos</li>
                        </ul>
                    </div>
                    <div class="alert alert-info small mb-0">
                        <strong>Consejo:</strong> Agregue una descripción clara para que el acudiente entienda los cargos.
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
            
            // Aquí iría lógica para mostrar lista de acudientes
            console.log('Acudientes encontrados:', data);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Enviar formulario
    document.getElementById('formFactura').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const acudienteId = document.getElementById('acudienteId').value;
        if (!acudienteId) {
            alert('Por favor seleccione un acudiente');
            return;
        }

        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("tesoreria.factura.matricula") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    acudiente_id: formData.get('acudiente_id'),
                    matricula_id: formData.get('matricula_id') || null,
                    monto: parseFloat(formData.get('monto')),
                    descripcion: formData.get('descripcion'),
                }),
            });

            const data = await response.json();
            
            if (data.factura) {
                document.getElementById('facturaId').textContent = data.factura.id;
                document.getElementById('successAlert').classList.remove('d-none');
                this.reset();
                document.getElementById('acudienteId').value = '';
                
                // Recargar tabla de facturas
                setTimeout(() => location.reload(), 2000);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    // Cargar facturas recientes
    async function cargarFacturas() {
        try {
            const response = await fetch('/api/pagos?tipo=factura');
            const data = await response.json();
            
            const table = document.getElementById('facturasTable');
            table.innerHTML = '';
            
            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No hay facturas</td></tr>';
                return;
            }

            data.forEach(factura => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>#${factura.id}</td>
                    <td>${factura.acudiente?.name || 'N/A'}</td>
                    <td>$${parseFloat(factura.monto).toFixed(2)}</td>
                    <td>${factura.descripcion || '-'}</td>
                    <td><span class="badge bg-warning">${factura.estado}</span></td>
                `;
                table.appendChild(row);
            });
        } catch (error) {
            console.error('Error al cargar facturas:', error);
        }
    }

    // Cargar facturas al abrir la página
    cargarFacturas();
</script>
@endsection

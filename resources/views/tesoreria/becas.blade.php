@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-graduation-cap me-2 text-info"></i>Becas y Descuentos
            </h1>
            <p class="text-muted">Registre y gestione becas y descuentos académicos para acudientes</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Registrar Nueva Beca</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formBeca">
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
                                <label class="form-label fw-bold">Tipo de Beca <span class="text-danger">*</span></label>
                                <select name="tipo_beca" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="Beca 100%">Beca del 100%</option>
                                    <option value="Beca 75%">Beca del 75%</option>
                                    <option value="Beca 50%">Beca del 50%</option>
                                    <option value="Beca 25%">Beca del 25%</option>
                                    <option value="Descuento Especial">Descuento Especial</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Monto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="monto" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción / Motivo <span class="text-danger">*</span></label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: Beca por mérito académico..." required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vigencia Desde</label>
                                <input type="date" name="desde" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vigencia Hasta</label>
                                <input type="date" name="hasta" class="form-control">
                            </div>
                        </div>

                        <div class="alert alert-success d-none" id="successAlert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Beca registrada exitosamente
                        </div>

                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-save me-2"></i>Registrar Beca
                        </button>
                    </form>
                </div>
            </div>

            {{-- Becas registradas --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Becas Registradas</h5>
                    <select id="filterEstado" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Todas</option>
                        <option value="activa">Activas</option>
                        <option value="vencida">Vencidas</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Acudiente</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Vigencia</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="becasTable">
                            <tr><td colspan="6" class="text-center text-muted py-4">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resumen de Becas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <h6 class="text-muted text-uppercase small">Total Otorgado</h6>
                        <h3 class="text-info fw-bold">$<span id="totalBecas">0.00</span></h3>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="text-muted text-uppercase small">Becas Activas</h6>
                        <h3 class="fw-bold"><span id="countBecas">0</span></h3>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tipos de Beca</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-2">
                        <strong>Beca 100%</strong>
                        <p class="text-muted mb-0 small">Cobertura total del costo académico</p>
                    </div>
                    <div class="mb-2">
                        <strong>Beca 75%</strong>
                        <p class="text-muted mb-0 small">Descuento del 75% en aranceles</p>
                    </div>
                    <div class="mb-2">
                        <strong>Beca 50%</strong>
                        <p class="text-muted mb-0 small">Descuento del 50% en aranceles</p>
                    </div>
                    <div class="mb-2">
                        <strong>Beca 25%</strong>
                        <p class="text-muted mb-0 small">Descuento del 25% en aranceles</p>
                    </div>
                    <div>
                        <strong>Descuento Especial</strong>
                        <p class="text-muted mb-0 small">Descuentos bajo criterios especiales</p>
                    </div>
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
            console.log('Acudientes encontrados:', data);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    document.getElementById('formBeca').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const acudienteId = document.getElementById('acudienteId').value;
        if (!acudienteId) {
            alert('Por favor seleccione un acudiente');
            return;
        }

        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("tesoreria.beca.registrar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    matricula_id: formData.get('matricula_id') || null,
                    acudiente_id: formData.get('acudiente_id'),
                    monto: parseFloat(formData.get('monto')),
                    descripcion: formData.get('descripcion'),
                }),
            });

            const data = await response.json();
            
            if (data.beca) {
                document.getElementById('successAlert').classList.remove('d-none');
                this.reset();
                document.getElementById('acudienteId').value = '';
                
                setTimeout(() => {
                    cargarBecas();
                    document.getElementById('successAlert').classList.add('d-none');
                }, 2000);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    async function cargarBecas() {
        try {
            const response = await fetch('/api/pagos?tipo=beca');
            const data = await response.json();
            
            const table = document.getElementById('becasTable');
            table.innerHTML = '';
            
            let total = 0;
            let count = 0;

            if (data.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay becas registradas</td></tr>';
            } else {
                data.forEach(beca => {
                    total += Math.abs(parseFloat(beca.monto));
                    count++;
                    const row = document.createElement('tr');
                    const estado = new Date() < new Date(beca.vigencia_hasta || '2099-12-31') ? 'Activa' : 'Vencida';
                    const estadoBadge = estado === 'Activa' ? 'badge-success' : 'badge-warning';
                    
                    row.innerHTML = `
                        <td>${beca.acudiente?.name || 'N/A'}</td>
                        <td>${beca.descripcion?.split(' ').slice(0, 2).join(' ') || 'Beca'}</td>
                        <td>$${Math.abs(parseFloat(beca.monto)).toFixed(2)}</td>
                        <td>--</td>
                        <td>${beca.descripcion || '-'}</td>
                        <td><span class="badge ${estadoBadge}">${estado}</span></td>
                    `;
                    table.appendChild(row);
                });
            }

            document.getElementById('totalBecas').textContent = total.toFixed(2);
            document.getElementById('countBecas').textContent = count;
        } catch (error) {
            console.error('Error al cargar becas:', error);
        }
    }

    cargarBecas();
</script>
@endsection

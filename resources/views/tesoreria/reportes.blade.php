@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar me-2 text-secondary"></i>Reportes Financieros
            </h1>
            <p class="text-muted">Genere reportes financieros y de movimientos por período</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Generar Reporte</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="formReporte">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Desde <span class="text-danger">*</span></label>
                                <input type="date" id="desde" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hasta <span class="text-danger">*</span></label>
                                <input type="date" id="hasta" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Reporte</label>
                                <select id="tipoReporte" class="form-select">
                                    <option value="general">Reporte General</option>
                                    <option value="movimientos">Movimientos Detallados</option>
                                    <option value="acudientes">Por Acudiente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Formato</label>
                                <select id="formato" class="form-select">
                                    <option value="html">Ver en Pantalla</option>
                                    <option value="pdf">Descargar PDF</option>
                                    <option value="excel">Descargar Excel</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-chart-line me-2"></i>Generar Reporte
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contenedor de reporte --}}
            <div id="reporteContent" class="d-none">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="reporteTitle"></h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Imprimir
                            </button>
                            <button class="btn btn-sm btn-outline-success ms-2" onclick="exportarReporte()">
                                <i class="fas fa-download me-2"></i>Descargar
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="reporteBody">
                        {{-- Se llena dinámicamente --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Atajos Rápidos</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="reporteMesActual()">
                        <i class="fas fa-calendar-alt me-2"></i>Este Mes
                    </button>
                    <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="reporteTrimestreActual()">
                        <i class="fas fa-calendar-alt me-2"></i>Este Trimestre
                    </button>
                    <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="reporteAnioActual()">
                        <i class="fas fa-calendar-alt me-2"></i>Este Año
                    </button>
                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="reporteMesAnterior()">
                        <i class="fas fa-calendar-alt me-2"></i>Mes Anterior
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Reporte General:</strong> Resumen de ingresos y egresos del período.</p>
                    <p><strong>Movimientos:</strong> Detalle de todas las transacciones realizadas.</p>
                    <p><strong>Por Acudiente:</strong> Movimientos agrupados por cada acudiente.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Establecer fechas predeterminadas
    const hoy = new Date();
    const primeroDelMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    
    document.getElementById('desde').valueAsDate = primeroDelMes;
    document.getElementById('hasta').valueAsDate = hoy;

    // Funciones de atajos
    function reporteMesActual() {
        document.getElementById('desde').valueAsDate = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        document.getElementById('hasta').valueAsDate = hoy;
    }

    function reporteTrimestreActual() {
        const trimestre = Math.floor(hoy.getMonth() / 3);
        document.getElementById('desde').valueAsDate = new Date(hoy.getFullYear(), trimestre * 3, 1);
        document.getElementById('hasta').valueAsDate = hoy;
    }

    function reporteAnioActual() {
        document.getElementById('desde').valueAsDate = new Date(hoy.getFullYear(), 0, 1);
        document.getElementById('hasta').valueAsDate = hoy;
    }

    function reporteMesAnterior() {
        const mesAnterior = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
        const ultimoDiaAnterior = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
        document.getElementById('desde').valueAsDate = mesAnterior;
        document.getElementById('hasta').valueAsDate = ultimoDiaAnterior;
    }

    // Generar reporte
    document.getElementById('formReporte').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const desde = document.getElementById('desde').value;
        const hasta = document.getElementById('hasta').value;
        const tipoReporte = document.getElementById('tipoReporte').value;
        const formato = document.getElementById('formato').value;

        try {
            const response = await fetch(`{{ route('tesoreria.reportes') }}?desde=${desde}&hasta=${hasta}`);
            const data = await response.json();

            // Generar contenido HTML
            let html = `
                <table class="table table-striped">
                    <tr>
                        <th>Concepto</th>
                        <th>Estado</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
            `;

            data.reporte.forEach(item => {
                html += `
                    <tr>
                        <td>${item.tipo}</td>
                        <td><span class="badge bg-info">${item.estado}</span></td>
                        <td>${item.count}</td>
                        <td>$${parseFloat(item.total).toFixed(2)}</td>
                    </tr>
                `;
            });

            html += `</table>`;
            
            // Mostrar reporte
            document.getElementById('reporteTitle').textContent = `Reporte: ${desde} a ${hasta}`;
            document.getElementById('reporteBody').innerHTML = html;
            document.getElementById('reporteContent').classList.remove('d-none');

            // Manejo de formatos
            if (formato === 'pdf') {
                alert('Exportar a PDF (función en desarrollo)');
            } else if (formato === 'excel') {
                alert('Exportar a Excel (función en desarrollo)');
            }
        } catch (error) {
            alert('Error al generar reporte: ' + error.message);
        }
    });

    function exportarReporte() {
        alert('Exportar reporte (función en desarrollo)');
    }
</script>
@endsection

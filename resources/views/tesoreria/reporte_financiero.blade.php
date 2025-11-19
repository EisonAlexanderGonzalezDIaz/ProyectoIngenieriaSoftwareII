@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2 text-success"></i>Reporte Financiero General
            </h1>
            <p class="text-muted">Consulte un resumen consolidado de ingresos y egresos del período</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Resumen Financiero</h5>
                    <a href="{{ route('tesoreria.dashboard') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Desde</label>
                            <input type="date" id="desde" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hasta</label>
                            <input type="date" id="hasta" class="form-control" required>
                        </div>
                    </div>

                    <button class="btn btn-success" onclick="cargarReporte()">
                        <i class="fas fa-sync me-2"></i>Generar Reporte
                    </button>
                </div>
            </div>

            {{-- Tarjetas de resumen --}}
            <div class="row mb-4" id="resumenCards" style="display: none;">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small mb-2">Ingresos</h6>
                            <h3 class="text-success fw-bold">$<span id="ingresos">0.00</span></h3>
                            <small class="text-muted">Pagos recibidos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small mb-2">Egresos</h6>
                            <h3 class="text-danger fw-bold">$<span id="egresos">0.00</span></h3>
                            <small class="text-muted">Becas y descuentos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted text-uppercase small mb-2">Neto</h6>
                            <h3 class="fw-bold" id="neto" style="color: #17a2b8;">$0.00</h3>
                            <small class="text-muted">Saldo del período</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráfico (simulado) --}}
            <div class="card border-0 shadow-sm mb-4" id="graficaContainer" style="display: none;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Gráfica de Ingresos vs Egresos</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="miGrafica" height="100"></canvas>
                </div>
            </div>

            {{-- Tabla de detalles --}}
            <div class="card border-0 shadow-sm" id="tablaContainer" style="display: none;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detalle Consolidado</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>% del Total</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDetalles">
                            {{-- Se llena dinámicamente --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Establecer fechas predeterminadas
    const hoy = new Date();
    const primeroDelMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    
    document.getElementById('desde').valueAsDate = primeroDelMes;
    document.getElementById('hasta').valueAsDate = hoy;

    let grafica = null;

    async function cargarReporte() {
        const desde = document.getElementById('desde').value;
        const hasta = document.getElementById('hasta').value;

        try {
            const response = await fetch(`{{ route('tesoreria.view.reporte') }}?desde=${desde}&hasta=${hasta}`);
            const data = await response.json();

            const totales = data.totales;
            const ingresos = totales.ingresos ? parseFloat(totales.ingresos) : 0;
            const egresos = totales.egresos ? Math.abs(parseFloat(totales.egresos)) : 0;
            const neto = ingresos - egresos;

            // Mostrar tarjetas
            document.getElementById('ingresos').textContent = ingresos.toFixed(2);
            document.getElementById('egresos').textContent = egresos.toFixed(2);
            document.getElementById('neto').textContent = neto.toFixed(2);
            
            const netoClass = neto >= 0 ? 'text-success' : 'text-danger';
            document.getElementById('neto').parentElement.parentElement.className = 'card-body text-center ' + netoClass;

            // Mostrar contenedores
            document.getElementById('resumenCards').style.display = '';
            document.getElementById('graficaContainer').style.display = '';
            document.getElementById('tablaContainer').style.display = '';

            // Crear gráfica
            const ctx = document.getElementById('miGrafica').getContext('2d');
            
            if (grafica) {
                grafica.destroy();
            }

            grafica = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ingresos', 'Egresos'],
                    datasets: [{
                        label: 'Monto ($)',
                        data: [ingresos, egresos],
                        backgroundColor: ['#28a745', '#dc3545'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });

            // Llenar tabla de detalles
            const tabla = document.getElementById('tablaDetalles');
            tabla.innerHTML = `
                <tr>
                    <td><strong>Total Ingresos</strong></td>
                    <td class="text-success fw-bold">$${ingresos.toFixed(2)}</td>
                    <td>${((ingresos / (ingresos + egresos)) * 100).toFixed(1)}%</td>
                </tr>
                <tr>
                    <td><strong>Total Egresos</strong></td>
                    <td class="text-danger fw-bold">-$${egresos.toFixed(2)}</td>
                    <td>${((egresos / (ingresos + egresos)) * 100).toFixed(1)}%</td>
                </tr>
                <tr class="table-active">
                    <td><strong>Neto</strong></td>
                    <td class="fw-bold">$${neto.toFixed(2)}</td>
                    <td>100%</td>
                </tr>
            `;

        } catch (error) {
            alert('Error al cargar reporte: ' + error.message);
        }
    }

    // Cargar reporte al abrir la página
    cargarReporte();
</script>
@endsection

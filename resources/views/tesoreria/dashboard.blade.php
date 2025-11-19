@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0">
                <i class="fas fa-cash-register me-2"></i>Tesorería - Panel Principal
            </h1>
            <p class="text-muted">Gestione pagos, facturas, becas y reportes financieros del colegio</p>
        </div>
    </div>

    {{-- Tarjetas de resumen rápido --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice-dollar fa-2x text-primary mb-3"></i>
                    <h6 class="text-muted text-uppercase small mb-2">Total Pendiente</h6>
                    <h3 class="mb-0 text-primary fw-bold">$0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h6 class="text-muted text-uppercase small mb-2">Pagos Este Mes</h6>
                    <h3 class="mb-0 text-success fw-bold">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-undo-alt fa-2x text-warning mb-3"></i>
                    <h6 class="text-muted text-uppercase small mb-2">Devoluciones</h6>
                    <h3 class="mb-0 text-warning fw-bold">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-2x text-info mb-3"></i>
                    <h6 class="text-muted text-uppercase small mb-2">Becas Otorgadas</h6>
                    <h3 class="mb-0 text-info fw-bold">0</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid de funcionalidades principales --}}
    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                    <h5 class="card-title mb-3">Paz y Salvo</h5>
                    <p class="card-text text-muted small mb-3">Genere certificados de paz y salvo para acudientes sin deudas</p>
                    <a href="{{ route('tesoreria.view.pazysalvo') }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-receipt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title mb-3">Factura de Matrícula</h5>
                    <p class="card-text text-muted small mb-3">Cree y gestione facturas de matrícula para nuevos estudiantes</p>
                    <a href="{{ route('tesoreria.view.factura') }}" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-credit-card fa-3x text-info mb-3"></i>
                    <h5 class="card-title mb-3">Registrar Pagos</h5>
                    <p class="card-text text-muted small mb-3">Registre pagos realizados por acudientes e instituciones</p>
                    <a href="{{ route('tesoreria.view.pagos') }}" class="btn btn-info btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-undo-alt fa-3x text-warning mb-3"></i>
                    <h5 class="card-title mb-3">Devoluciones</h5>
                    <p class="card-text text-muted small mb-3">Gestione y procese devoluciones de pagos</p>
                    <a href="{{ route('tesoreria.view.devoluciones') }}" class="btn btn-warning btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-wallet fa-3x text-danger mb-3"></i>
                    <h5 class="card-title mb-3">Cartera</h5>
                    <p class="card-text text-muted small mb-3">Consulte el estado de cartera y pagos pendientes</p>
                    <a href="{{ route('tesoreria.view.cartera') }}" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-chart-bar fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title mb-3">Reportes</h5>
                    <p class="card-text text-muted small mb-3">Genere reportes financieros y de movimientos</p>
                    <a href="{{ route('tesoreria.view.reportes') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-user-tie fa-3x text-purple mb-3" style="color: #6f42c1;"></i>
                    <h5 class="card-title mb-3">Estado de Cuenta</h5>
                    <p class="card-text text-muted small mb-3">Consulte estado de cuenta por acudiente</p>
                    <a href="{{ route('tesoreria.view.estado') }}" class="btn btn-outline-purple btn-sm w-100" style="color: #6f42c1; border-color: #6f42c1;">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-graduation-cap fa-3x text-teal mb-3" style="color: #17a2b8;"></i>
                    <h5 class="card-title mb-3">Becas y Descuentos</h5>
                    <p class="card-text text-muted small mb-3">Registre y gestione becas y descuentos académicos</p>
                    <a href="{{ route('tesoreria.view.becas') }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow-lg transition">
                <div class="card-body text-center p-4">
                    <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                    <h5 class="card-title mb-3">Reporte Financiero</h5>
                    <p class="card-text text-muted small mb-3">Consulte reportes financieros resumidos</p>
                    <a href="{{ route('tesoreria.view.reporte') }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow-lg:hover {
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .transition {
        transition: all 0.3s ease;
    }

    .btn-outline-purple {
        color: #6f42c1;
        border-color: #6f42c1;
    }

    .btn-outline-purple:hover {
        background-color: #6f42c1;
        color: white;
    }
</style>
@endsection

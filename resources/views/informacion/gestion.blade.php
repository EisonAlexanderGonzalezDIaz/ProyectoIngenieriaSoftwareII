{{-- resources/views/informacion/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ==============================
         CONSULTAR INFORMACIÓN COLEGIO
    =============================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0">
                <i class="fas fa-school me-2"></i>Información Colegio San Martin
            </h4>
            <div>
                {{-- Botón para volver al panel de inicio --}}
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-home"></i> Panel de inicio
                </a>
            </div>
        </div>

        <div class="card-body" style="background-color:#f4f8ff;">

            {{-- Barra de búsqueda simple --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body bg-white">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small mb-1">
                                Buscar en la información
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInfoColegio" class="form-control" placeholder="Ej: pensión, jornada, director, misión...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-secondary small mb-1">
                                Año vigente
                            </label>
                            <select class="form-select form-select-sm" id="filterAnio">
                                <option value="">2025 (actual)</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-sm btn-primary" type="button" onclick="exportInfoColegio()">
                                <i class="fas fa-file-export me-1"></i> Exportar información
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Datos básicos del colegio --}}
            <div class="row mb-4" id="bloqueDatosBasicos">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#1976d2;">
                                <i class="fas fa-id-card text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Datos generales</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <p class="mb-1">
                                <strong>Nombre:</strong> Colegio Distrital San Martín
                            </p>
                            <p class="mb-1">
                                <strong>Código DANE:</strong> 111001234567
                            </p>
                            <p class="mb-1">
                                <strong>NIT:</strong> 900.123.456-7
                            </p>
                            <p class="mb-1">
                                <strong>Calendario:</strong> A
                            </p>
                            <p class="mb-0">
                                <strong>Naturaleza:</strong> Oficial
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#1565c0;">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Ubicación y contacto</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <p class="mb-1">
                                <strong>Dirección:</strong> Calle 123 # 45-67, Barrio El Progreso
                            </p>
                            <p class="mb-1">
                                <strong>Ciudad:</strong> Chía – Cundinamarca
                            </p>
                            <p class="mb-1">
                                <strong>Teléfono:</strong> (601) 123 45 67
                            </p>
                            <p class="mb-1">
                                <strong>Correo institucional:</strong> info@colegiosanmartin.edu.co
                            </p>
                            <p class="mb-0">
                                <strong>Sitio web:</strong> www.colegiosanmartin.edu.co
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información académica / administrativa --}}
            <div class="row mb-4" id="bloqueInfoAcademica">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#0d47a1;">
                                <i class="fas fa-clock text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Jornadas y niveles</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <ul class="list-unstyled mb-2 small">
                                <li><strong>Jornada Mañana:</strong> 6:30 am – 12:30 pm</li>
                                <li><strong>Jornada Tarde:</strong> 1:00 pm – 6:00 pm</li>
                            </ul>
                            <hr class="my-2">
                            <p class="small mb-0">
                                <strong>Niveles:</strong> Preescolar, Básica Primaria, Básica Secundaria y Media.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#1e88e5;">
                                <i class="fas fa-users text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Directivos</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <p class="small mb-1">
                                <strong>Rector(a):</strong> Ana María Rodríguez
                            </p>
                            <p class="small mb-1">
                                <strong>Coordinador Académico:</strong> Carlos Pérez
                            </p>
                            <p class="small mb-1">
                                <strong>Coordinador de Convivencia:</strong> Laura Gómez
                            </p>
                            <p class="small mb-0">
                                <strong>Tesorero(a):</strong> Juan Fernández
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#1565c0;">
                                <i class="fas fa-hand-holding-heart text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Servicios complementarios</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <ul class="small mb-0">
                                <li>Restaurante escolar</li>
                                <li>Transporte escolar</li>
                                <li>Orientación escolar y psicología</li>
                                <li>Biblioteca y sala de sistemas</li>
                                <li>Actividades extracurriculares (deportes, música, arte)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información financiera / pensiones (simulada) --}}
            <div class="card shadow-sm border-0 mb-4" id="bloqueInfoFinanciera">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-secondary">
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i> Información financiera general
                    </h6>
                    <small class="text-muted">*Valores de referencia para el año 2025</small>
                </div>
                <div class="card-body" style="background-color:#e3f2fd;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover text-center bg-white mb-0">
                            <thead style="background-color:#1976d2; color:white;">
                                <tr>
                                    <th>Grado</th>
                                    <th>Matrícula (aprox.)</th>
                                    <th>Pensión mensual (aprox.)</th>
                                    <th>N° cuotas</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Básica Secundaria (6° a 9°)</td>
                                    <td>$ 340.000</td>
                                    <td>$ 270.000</td>
                                    <td>10</td>
                                    <td>Incluye uso de laboratorios</td>
                                </tr>
                                <tr>
                                    <td>Media (10° y 11°)</td>
                                    <td>$ 360.000</td>
                                    <td>$ 290.000</td>
                                    <td>10</td>
                                    <td>Incluye orientación vocacional</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Misión y Visión --}}
            <div class="row" id="bloqueMisionVision">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#1e88e5;">
                                <i class="fas fa-bullseye text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Misión institucional</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <p class="small mb-0" id="textoMision">
                                Formar integralmente a niños, niñas y jóvenes como ciudadanos críticos,
                                solidarios y respetuosos, capaces de transformar su entorno mediante el
                                desarrollo de competencias académicas, sociales y éticas, en un ambiente
                                de convivencia pacífica y respeto por la diversidad.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <span class="badge rounded-pill me-2" style="background-color:#0d47a1;">
                                <i class="fas fa-eye text-white"></i>
                            </span>
                            <h6 class="mb-0 text-secondary">Visión institucional</h6>
                        </div>
                        <div class="card-body" style="background-color:#e3f2fd;">
                            <p class="small mb-0" id="textoVision">
                                Ser en el año 2030 una institución educativa reconocida por la calidad de
                                sus procesos pedagógicos y de convivencia, que promueve el liderazgo,
                                la innovación y el compromiso social de sus estudiantes con la comunidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- card-body --}}
    </div> {{-- card --}}
</div> {{-- container --}}

{{-- =====================================
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    // Búsqueda simple sobre los bloques de texto de la vista
    document.getElementById('searchInfoColegio').addEventListener('keyup', function (e) {
        const value = e.target.value.toLowerCase();

        const contenedor = document.querySelector('.card-body');
        const elementos = contenedor.querySelectorAll('p, li, td, th, h6');

        elementos.forEach(el => {
            const texto = el.textContent.toLowerCase();
            if (value && texto.includes(value)) {
                el.style.backgroundColor = '#bbdefb';
            } else {
                el.style.backgroundColor = '';
            }
        });
    });

    // Filtro de año (simulado)
    document.getElementById('filterAnio').addEventListener('change', function () {
        const anio = this.value || '2025';
        alert('🔄 Filtro simulado por año: ' + anio + '\nMás adelante se conectará con la base de datos.');
    });

    // Exportación simulada
    function exportInfoColegio() {
        alert('📁 Exportación simulada de la información del colegio.\nLuego podrás generar PDF o Excel desde aquí.');
    }
</script>
@endsection


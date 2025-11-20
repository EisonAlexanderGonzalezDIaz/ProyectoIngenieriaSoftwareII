@extends('layouts.app')

@section('title', 'Panel Principal - Colegio')

@section('content')

@php
    $usuario   = Auth::user();
    $rolNombre = $usuario->rol->nombre ?? '';
@endphp

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-school me-2"></i>Colegio San Martín
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>{{ $usuario->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-graduate me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <div class="sidebar bg-light border-end vh-100">
                <div class="p-3 border-bottom">
                    <h6 class="text-primary text-uppercase fw-bold mb-0">Menú Principal</h6>
                </div>
                <nav class="nav flex-column px-3 mt-2">

                    {{-- ============================
                         INICIO
                    ============================= --}}
                    <a class="nav-link active fw-semibold text-primary" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>

                    {{-- =========================================================
                         CONSULTAR ESTUDIANTES (varía según el rol)
                    ========================================================== --}}
                    @if(in_array($rolNombre, [
                        'Rector', 'CoordinadorAcademico', 'CoordinadorDisciplinario',
                        'Orientador', 'Tesorero', 'Docente', 'AdministradorSistema'
                    ]))
                        @if($rolNombre === 'AdministradorSistema')
                            {{-- AdminSistema va al menú especial --}}
                            <a class="nav-link text-dark" href="{{ route('admin.estudiantes.menu') }}">
                                <i class="fas fa-user-graduate me-2"></i>Consultar estudiantes
                            </a>

                        @elseif($rolNombre === 'Tesorero')
                            {{-- Tesorería: Consultar estudiantes con submenú financiero --}}
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuTesoreriaEstudiantes"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuTesoreriaEstudiantes">
                                <span><i class="fas fa-user-graduate me-2"></i>Consultar estudiantes</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>

                            <div class="collapse ps-4" id="menuTesoreriaEstudiantes">
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-list me-2"></i>Listado de estudiantes
                                </a>

                                {{-- Registrar pagos y devoluciones --}}
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-credit-card me-2"></i>Registrar pagos estudiantes
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-undo-alt me-2"></i>Gestionar devoluciones
                                </a>

                                {{-- Ver información financiera --}}
                                <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                                   data-bs-toggle="collapse"
                                   href="#menuTesoreriaFinanciera"
                                   role="button"
                                   aria-expanded="false"
                                   aria-controls="menuTesoreriaFinanciera">
                                    <span><i class="fas fa-coins me-2"></i>Ver información financiera</span>
                                    <i class="fas fa-chevron-down small"></i>
                                </a>

                                <div class="collapse ps-4" id="menuTesoreriaFinanciera">
                                    <a class="nav-link text-dark" href="#">
                                        <i class="fas fa-wallet me-2"></i>Estado de cuenta
                                    </a>

                                    {{-- Paz y salvos --}}
                                    <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                                       data-bs-toggle="collapse"
                                       href="#menuTesoreriaPazSalvo"
                                       role="button"
                                       aria-expanded="false"
                                       aria-controls="menuTesoreriaPazSalvo">
                                        <span><i class="fas fa-file-invoice me-2"></i>Paz y salvos</span>
                                        <i class="fas fa-chevron-down small"></i>
                                    </a>
                                    <div class="collapse ps-4" id="menuTesoreriaPazSalvo">
                                        <a class="nav-link text-dark" href="#">
                                            <i class="fas fa-eye me-2"></i>Consultar paz y salvos
                                        </a>
                                        <a class="nav-link text-dark" href="#">
                                            <i class="fas fa-file-signature me-2"></i>Generar paz y salvo
                                        </a>
                                    </div>

                                    {{-- Recibos de matrícula --}}
                                    <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                                       data-bs-toggle="collapse"
                                       href="#menuTesoreriaRecibos"
                                       role="button"
                                       aria-expanded="false"
                                       aria-controls="menuTesoreriaRecibos">
                                        <span><i class="fas fa-receipt me-2"></i>Recibos de matrícula</span>
                                        <i class="fas fa-chevron-down small"></i>
                                    </a>
                                    <div class="collapse ps-4" id="menuTesoreriaRecibos">
                                        <a class="nav-link text-dark" href="#">
                                            <i class="fas fa-eye me-2"></i>Consultar recibos
                                        </a>
                                        <a class="nav-link text-dark" href="#">
                                            <i class="fas fa-file-invoice-dollar me-2"></i>Generar recibos
                                        </a>
                                    </div>

                                    {{-- Gestionar cartera --}}
                                    <a class="nav-link text-dark" href="#">
                                        <i class="fas fa-wallet me-2"></i>Gestionar cartera
                                    </a>
                                </div>
                            </div>

                        @elseif($rolNombre === 'Rector')
                            {{-- Rector: Consultar estudiantes -> boletines / notas / materias --}}
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuRectorEstudiantes"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuRectorEstudiantes">
                                <span><i class="fas fa-user-graduate me-2"></i>Consultar estudiantes</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuRectorEstudiantes">
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-newspaper me-2"></i>Consultar boletines
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-clipboard-list me-2"></i>Consultar notas
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-book me-2"></i>Consultar materias
                                </a>
                            </div>

                        @elseif($rolNombre === 'CoordinadorAcademico')
                            {{-- Coordinador académico: consultar estudiantes + recuperaciones --}}
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuCoordAcadEstudiantes"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuCoordAcadEstudiantes">
                                <span><i class="fas fa-user-graduate me-2"></i>Consultar estudiantes</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuCoordAcadEstudiantes">
                                <a class="nav-link text-dark" href="{{ route('estudiantes.gestion') }}">
                                    <i class="fas fa-list me-2"></i>Listado de estudiantes
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-sync-alt me-2"></i>Gestionar recuperaciones
                                </a>
                            </div>

                        @elseif($rolNombre === 'Docente')
                            {{-- Docente: Consultar estudiantes -> notas, materia, asistencia --}}
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuDocenteEstudiantes"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuDocenteEstudiantes">
                                <span><i class="fas fa-user-graduate me-2"></i>Consultar estudiantes</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuDocenteEstudiantes">
                                <a class="nav-link text-dark" href="{{ route('docente.registrar_notas') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>Consultar notas
                                </a>
                                <a class="nav-link text-dark" href="{{ route('docente.subir_material') }}">
                                    <i class="fas fa-book me-2"></i>Consultar materia (subir material)
                                </a>
                                <a class="nav-link text-dark" href="{{ route('docente.registrar_notas') }}">
                                    <i class="fas fa-pen me-2"></i>Registrar notas
                                </a>
                                <a class="nav-link text-dark" href="{{ route('docente.registrar_asistencia') }}">
                                    <i class="fas fa-user-check me-2"></i>Registrar asistencia
                                </a>
                                <a class="nav-link text-dark" href="{{ route('docente.registrar_asistencia') }}">
                                    <i class="fas fa-user-clock me-2"></i>Consultar asistencia
                                </a>
                            </div>

                        @else
                            {{-- Otros roles usan la vista normal de estudiantes --}}
                            <a class="nav-link text-dark" href="{{ route('estudiantes.gestion') }}">
                                <i class="fas fa-user-graduate me-2"></i>Consultar estudiantes
                            </a>
                        @endif
                    @endif

                    {{-- =========================================
                         ADMINISTRADOR DEL SISTEMA (roles / perfiles)
                    ========================================== --}}
                    @if($rolNombre === 'AdministradorSistema')
                        <a class="nav-link text-dark" href="{{ route('roles.create') }}">
                            <i class="fas fa-user-shield me-2"></i>Asignar permisos y roles
                        </a>

                        <a class="nav-link text-dark" href="{{ route('admin.usuarios.perfiles') }}">
                            <i class="fas fa-users-cog me-2"></i>Gestionar perfiles de usuario
                        </a>
                    @endif

                    {{-- =========================================
                         GESTIÓN DOCENTE / ACADÉMICA
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Rector', 'CoordinadorAcademico']))
                        <a class="nav-link text-dark" href="{{ route('gestiondocentes.gestion') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Gestionar docentes
                        </a>
                    @endif

                    @if($rolNombre === 'CoordinadorAcademico')
                        <a class="nav-link text-dark" href="{{ route('materias.gestion') }}">
                            <i class="fas fa-book-open me-2"></i>Gestionar materias
                        </a>

                        <a class="nav-link text-dark" href="{{ route('horarios.gestion') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Gestionar horarios (estudiantes y docentes)
                        </a>

                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-graduation-cap me-2"></i>Consultar plan académico anual
                        </a>
                    @endif

                    {{-- =========================================
                         Citas de orientación (texto según rol)
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Docente', 'Estudiante', 'Acudiente']))
                        @php
                            if ($rolNombre === 'Acudiente') {
                                $labelCita = 'Notificación de citas orientación';
                            } elseif ($rolNombre === 'Docente') {
                                $labelCita = 'Registrar caso en orientación';
                            } else { // Estudiante
                                $labelCita = 'Solicitar citas orientación';
                            }

                            $hrefCita = $rolNombre === 'Estudiante'
                                ? route('estudiante.solicitar_cita')
                                : '#';
                        @endphp
                        <a class="nav-link text-dark" href="{{ $hrefCita }}">
                            <i class="fas fa-calendar-plus me-2"></i>{{ $labelCita }}
                        </a>
                    @endif

                    {{-- =========================================
                         HORARIO DOCENTE (con descargar)
                    ========================================== --}}
                    @if($rolNombre === 'Docente')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuDocenteHorario"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuDocenteHorario">
                            <span><i class="fas fa-clock me-2"></i>Consultar horario</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuDocenteHorario">
                            <a class="nav-link text-dark" href="{{ route('docente.consultar_horario') }}">
                                <i class="fas fa-eye me-2"></i>Ver horario
                            </a>
                            <a class="nav-link text-dark" href="{{ route('docente.descargar_horario') }}">
                                <i class="fas fa-download me-2"></i>Descargar horario
                            </a>
                        </div>
                    @endif

                    {{-- =========================================
                         CONSULTAR MATERIAS (solo Estudiante aquí)
                    ========================================== --}}
                    @if($rolNombre === 'Estudiante')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuEstudianteMaterias"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuEstudianteMaterias">
                            <span><i class="fas fa-book me-2"></i>Consultar materias</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuEstudianteMaterias">
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-list me-2"></i>Listado de materias
                            </a>

                            <a class="nav-link text-dark" href="{{ route('estudiante.consultar_horario') }}">
                                <i class="fas fa-clock me-2"></i>Consultar / descargar horario
                            </a>

                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuEstudianteNotas"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuEstudianteNotas">
                                <span><i class="fas fa-clipboard-list me-2"></i>Consultar notas</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuEstudianteNotas">
                                <a class="nav-link text-dark" href="{{ route('estudiante.consultar_notas') }}">
                                    <i class="fas fa-list-ol me-2"></i>Notas por materia
                                </a>
                                <a class="nav-link text-dark" href="{{ route('estudiante.consultar_boletines') }}">
                                    <i class="fas fa-newspaper me-2"></i>Consultar boletines
                                </a>
                            </div>

                            <a class="nav-link text-dark" href="{{ route('estudiante.tareas') }}">
                                <i class="fas fa-folder-open me-2"></i>Material académico (subir/descargar)
                            </a>
                        </div>
                    @endif

                    {{-- =========================================
                         INFORMES / REPORTES (Docente)
                    ========================================== --}}
                    @if($rolNombre === 'Docente')
                        {{-- Generar informes de curso + reportes disciplinarios --}}
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuDocenteInformes"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuDocenteInformes">
                            <span><i class="fas fa-file-alt me-2"></i>Generar informes de curso</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuDocenteInformes">
                            <a class="nav-link text-dark" href="{{ route('docente.generar_informe') }}">
                                <i class="fas fa-file-alt me-2"></i>Informes académicos
                            </a>
                            <a class="nav-link text-dark" href="{{ route('reportes.gestion') }}">
                                <i class="fas fa-clipboard-list me-2"></i>Generar reportes disciplinarios
                            </a>
                        </div>
                    @endif

                    {{-- =========================================
                         BOLETINES (Docente / Acudiente)
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Docente', 'Acudiente']))
                        <a class="nav-link text-dark" href="{{ $rolNombre === 'Acudiente' ? route('acudiente.boletines') : '#' }}">
                            <i class="fas fa-newspaper me-2"></i>Consultar boletines
                        </a>
                    @endif

                    {{-- =========================================
                         CERTIFICADOS / BECAS
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Estudiante', 'Acudiente']))
                        @if($rolNombre === 'Estudiante')
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuEstudianteCertificados"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuEstudianteCertificados">
                                <span><i class="fas fa-certificate me-2"></i>Certificados de estudio</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuEstudianteCertificados">
                                <a class="nav-link text-dark" href="{{ route('estudiante.solicitar_certificacion') }}">
                                    <i class="fas fa-file-alt me-2"></i>Solicitar certificado de estudio
                                </a>
                                <a class="nav-link text-dark" href="{{ route('estudiante.descargar_certificacion') }}">
                                    <i class="fas fa-download me-2"></i>Descargar certificados
                                </a>
                            </div>
                        @else
                            <a class="nav-link text-dark" href="{{ route('acudiente.solicitar_paz') }}">
                                <i class="fas fa-percent me-2"></i>Solicitud de paz y salvo
                            </a>
                        @endif
                    @endif

                    {{-- =========================================
                         REPORTES DISCIPLINARIOS / NOTIFICACIONES
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Estudiante', 'Acudiente']))
                        <a class="nav-link text-dark" href="{{ $rolNombre === 'Estudiante' ? route('estudiante.reportes_disciplinarios') : route('acudiente.reportes_disciplinarios') }}">
                            <i class="fas fa-exclamation-circle me-2"></i>Consultar reportes disciplinarios
                        </a>
                    @endif

                    @if(in_array($rolNombre, ['Estudiante', 'Acudiente', 'Docente', 'Orientador']))
                        <a class="nav-link text-dark" href="{{ $rolNombre === 'Estudiante' ? route('estudiante.notificaciones') : ($rolNombre === 'Acudiente' ? route('acudiente.notificaciones') : '#') }}">
                            <i class="fas fa-bell me-2"></i>Centro de notificaciones
                        </a>
                    @endif

                    {{-- =========================================
                         INFORMACIÓN DEL COLEGIO
                    ========================================== --}}
                    @if(in_array($rolNombre, ['Estudiante', 'Acudiente', 'Docente','Orientador', 'Rector', 'Tesorero']))
                        @if($rolNombre === 'Rector')
                            <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               href="#menuRectorInfo"
                               role="button"
                               aria-expanded="false"
                               aria-controls="menuRectorInfo">
                                <span><i class="fas fa-info-circle me-2"></i>Información institucional</span>
                                <i class="fas fa-chevron-down small"></i>
                            </a>
                            <div class="collapse ps-4" id="menuRectorInfo">
                                <a class="nav-link text-dark" href="{{ route('informacion.gestion') }}">
                                    <i class="fas fa-eye me-2"></i>Consultar información
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-edit me-2"></i>Registrar / actualizar información
                                </a>
                                <a class="nav-link text-dark" href="#">
                                    <i class="fas fa-check-circle me-2"></i>Aprobar plan académico anual
                                </a>
                            </div>
                        @else
                            <a class="nav-link text-dark" href="{{ route('informacion.gestion') }}">
                                <i class="fas fa-info-circle me-2"></i>Consultar información de colegio
                            </a>
                        @endif
                    @endif

                    {{-- =========================================
                         CAMBIOS DE NOTAS / CITAS / REPORTES / CASOS
                    ========================================== --}}
                    @if($rolNombre === 'CoordinadorAcademico')
                        <a class="nav-link text-dark" href="{{ route('cambios-notas.gestion') }}">
                            <i class="fas fa-check me-2"></i>Aprobar cambios de notas
                        </a>
                    @endif

                    {{-- Citar acudientes: Docentes, Coordinadores y Orientador --}}
                    @if(in_array($rolNombre, ['CoordinadorAcademico', 'CoordinadorDisciplinario', 'Docente', 'Orientador']))
                        <a class="nav-link text-dark" href="{{ route('citas.gestion') }}">
                            <i class="fas fa-envelope-open-text me-2"></i>Citar acudientes
                        </a>
                    @endif

                    @if($rolNombre === 'CoordinadorAcademico')
                        <a class="nav-link text-dark" href="{{ route('reportes-academicos.gestion') }}">
                            <i class="fas fa-chart-line me-2"></i>Generar reportes académicos
                        </a>
                    @endif

                    {{-- Coordinador disciplinario: Casos disciplinarios con subfuncionalidades --}}
                    @if($rolNombre === 'CoordinadorDisciplinario')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuCoordDisciplinarioCasos"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuCoordDisciplinarioCasos">
                            <span><i class="fas fa-exclamation-triangle me-2"></i>Casos disciplinarios</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuCoordDisciplinarioCasos">
                            <a class="nav-link text-dark" href="{{ route('casos.gestion') }}">
                                <i class="fas fa-inbox me-2"></i>Recibir y reportar casos
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-radiation me-2"></i>Revisar casos graves
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-gavel me-2"></i>Asignar sanciones
                            </a>
                        </div>

                        <a class="nav-link text-dark" href="{{ route('reportes.gestion') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Generar reportes disciplinarios
                        </a>
                    @endif

                    {{-- =========================================
                         ORIENTADOR
                    ========================================== --}}
                    @if($rolNombre === 'Orientador')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuOrientador"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuOrientador">
                            <span><i class="fas fa-book-reader me-2"></i>Gestionar orientaciones</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuOrientador">
                            <a class="nav-link text-dark" href="{{ route('orientacion.gestion') }}">
                                <i class="fas fa-inbox me-2"></i>Recibir / registrar orientaciones
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-radiation me-2"></i>Revisar casos graves
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-user-clock me-2"></i>Realizar seguimiento
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-user-friends me-2"></i>Atender sesiones
                            </a>
                        </div>
                    @endif

                    {{-- =========================================
                         TESORERÍA (reportes financieros)
                    ========================================== --}}
                    @if($rolNombre === 'Tesorero')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuTesoreriaReportes"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuTesoreriaReportes">
                            <span><i class="fas fa-chart-pie me-2"></i>Reportes financieros</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuTesoreriaReportes">
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-chart-line me-2"></i>Generar reportes
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-paper-plane me-2"></i>Enviar reportes
                            </a>
                        </div>
                    @endif

                    {{-- =========================================
                         ESTADO DE CUENTA (Acudiente)
                    ========================================== --}}
                    @if($rolNombre === 'Acudiente')
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-wallet me-2"></i>Estado de cuenta
                        </a>
                    @endif

                    {{-- =========================================
                         BECAS Y DESCUENTOS (Rector)
                    ========================================== --}}
                    @if($rolNombre === 'Rector')
                        <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                           data-bs-toggle="collapse"
                           href="#menuRectorBecas"
                           role="button"
                           aria-expanded="false"
                           aria-controls="menuRectorBecas">
                            <span><i class="fas fa-percent me-2"></i>Becas y descuentos</span>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse ps-4" id="menuRectorBecas">
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-list me-2"></i>Consultar solicitudes
                            </a>
                            <a class="nav-link text-dark" href="#">
                                <i class="fas fa-check-circle me-2"></i>Aprobar / gestionar becas
                            </a>
                        </div>
                    @endif

                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                {{-- =========================
                     CABECERA SEGÚN EL ROL
                ========================== --}}
                <div class="row mb-4 align-items-center">
                    <div class="col-12">
                        <h1 class="h3 text-primary fw-bold">
                            ¡Bienvenido(a), {{ $usuario->name }}!
                        </h1>

                        @if($rolNombre === 'AdministradorSistema')
                            <p class="text-muted">
                                Administra usuarios, roles y la configuración general del colegio.
                            </p>
                        @elseif($rolNombre === 'CoordinadorAcademico')
                            <p class="text-muted">
                                Supervisa la gestión académica: docentes, materias, horarios y planes de estudio.
                            </p>
                        @elseif($rolNombre === 'Orientador')
                            <p class="text-muted">
                                Gestiona procesos de orientación, seguimiento a estudiantes y citas con acudientes.
                            </p>
                        @elseif($rolNombre === 'Tesorero')
                            <p class="text-muted">
                                Administra pagos, paz y salvo, cartera y reportes financieros.
                            </p>
                        @elseif($rolNombre === 'Docente')
                            <p class="text-muted">
                                Gestiona tus grupos, calificaciones, asistencia y reportes académicos y disciplinarios.
                            </p>
                        @elseif($rolNombre === 'Acudiente')
                            <p class="text-muted">
                                Consulta información académica, disciplinaria y financiera de tus acudidos.
                            </p>
                        @elseif($rolNombre === 'Estudiante')
                            <p class="text-muted">
                                Revisa tu horario, notas, materiales y comunicaciones del colegio.
                            </p>
                        @elseif($rolNombre === 'CoordinadorDisciplinario')
                            <p class="text-muted">
                                Supervisa la disciplina estudiantil, casos y reportes disciplinarios.
                            </p>
                        @elseif($rolNombre === 'Rector')
                            <p class="text-muted">
                                Supervisa integralmente la gestión académica, disciplinaria, financiera e institucional del colegio.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards & Quick Actions (role-specific) -->
                @if($rolNombre === 'Orientador')
                    @php
                        $pendientes = \App\Models\Cita::where('orientador_id', auth()->id())->where('estado', 'pendiente')->count();
                        $programadas = \App\Models\Cita::where('orientador_id', auth()->id())->where('estado', 'programado')->count();
                        $realizadas = \App\Models\Cita::where('orientador_id', auth()->id())->where('estado', 'realizado')->count();
                        $estudiantesSeguimiento = \App\Models\Cita::where('orientador_id', auth()->id())->distinct('estudiante_id')->count('estudiante_id');
                    @endphp

                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2"><i class="fas fa-hourglass-half fa-2x"></i></div>
                                    <h5 class="card-title">Pendientes</h5>
                                    <h3 class="text-primary">{{ $pendientes }}</h3>
                                    <small class="text-muted">Solicitudes por revisar</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2"><i class="fas fa-calendar-check fa-2x"></i></div>
                                    <h5 class="card-title">Programadas</h5>
                                    <h3 class="text-success">{{ $programadas }}</h3>
                                    <small class="text-muted">Citas por atender</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2"><i class="fas fa-check fa-2x"></i></div>
                                    <h5 class="card-title">Realizadas</h5>
                                    <h3 class="text-warning">{{ $realizadas }}</h3>
                                    <small class="text-muted">Citas completadas</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2"><i class="fas fa-user-friends fa-2x"></i></div>
                                    <h5 class="card-title">Estudiantes</h5>
                                    <h3 class="text-info">{{ $estudiantesSeguimiento }}</h3>
                                    <small class="text-muted">En seguimiento</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-rocket me-2"></i>Acciones rápidas (Orientador)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ route('orientacion.gestion') }}" class="btn btn-primary btn-lg w-100">
                                                <i class="fas fa-book-reader me-2"></i>Gestionar orientaciones
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="#" class="btn btn-outline-success btn-lg w-100">
                                                <i class="fas fa-file-export me-2"></i>Exportar citas
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="#" class="btn btn-outline-info btn-lg w-100">
                                                <i class="fas fa-plus me-2"></i>Nueva orientación
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- default statistics and quick actions for other roles -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2"><i class="fas fa-user-graduate fa-2x"></i></div>
                                    <h5 class="card-title">Estudiantes</h5>
                                    <h3 class="text-primary">420</h3>
                                    <small class="text-muted">Registrados en el sistema</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2"><i class="fas fa-user-tie fa-2x"></i></div>
                                    <h5 class="card-title">Docentes</h5>
                                    <h3 class="text-success">35</h3>
                                    <small class="text-muted">Activos actualmente</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2"><i class="fas fa-book fa-2x"></i></div>
                                    <h5 class="card-title">Materias</h5>
                                    <h3 class="text-warning">52</h3>
                                    <small class="text-muted">Asignaturas registradas</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2"><i class="fas fa-calendar-check fa-2x"></i></div>
                                    <h5 class="card-title">Eventos</h5>
                                    <h3 class="text-info">5</h3>
                                    <small class="text-muted">Programados este mes</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-rocket me-2"></i>Acciones rápidas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <a href="#" class="btn btn-primary btn-lg w-100">
                                                <i class="fas fa-user-plus me-2"></i>Registrar estudiante
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="#" class="btn btn-outline-primary btn-lg w-100">
                                                <i class="fas fa-book me-2"></i>Agregar materia
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="#" class="btn btn-outline-success btn-lg w-100">
                                                <i class="fas fa-chart-pie me-2"></i>Ver reportes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0 text-primary">
                                    <i class="fas fa-bell me-2"></i>Actividad reciente
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center py-4">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hay actividades recientes para mostrar.</p>
                                    <p class="text-muted">¡Comienza registrando estudiantes o docentes!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection

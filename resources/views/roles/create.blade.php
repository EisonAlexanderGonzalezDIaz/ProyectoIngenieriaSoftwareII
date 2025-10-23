@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="container-fluid py-3">
  {{-- Migas de pan --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="#">Roles</a></li>
      <li class="breadcrumb-item active" aria-current="page">Crear Nuevo Rol</li>
    </ol>
  </nav>

  @if(session('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('ok') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="formRol" method="POST" action="{{ route('roles.quick-create') }}">
    @csrf
    <div class="row g-3">
      {{-- Panel izquierdo: Información Básica --}}
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-2"></i> Información Básica
          </div>
          <div class="card-body">
            {{-- Nombre del Rol --}}
            <div class="mb-3">
              <label class="form-label">Nombre del Rol *</label>
              <input type="text" name="nombre" id="nombreRol" class="form-control" placeholder="Ej: Rector, CoordinadorAcademico, Docente" required list="roles-sugeridos">
              <div class="form-text">
                Usa exactamente los nombres que manejas (p. ej. <code>AdministradorSistema</code>, <code>CoordinadorAcademico</code>).
              </div>
              <datalist id="roles-sugeridos">
                <option value="Rector">
                <option value="Acudiente">
                <option value="Estudiante">
                <option value="CoordinadorAcademico">
                <option value="CoordinadorDisciplinario">
                <option value="Orientador">
                <option value="Docente">
                <option value="Tesorero">
                <option value="AdministradorSistema">
              </datalist>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
              <label class="form-label">Descripción *</label>
              <textarea name="descripcion" class="form-control" rows="3" maxlength="500" placeholder="Describe responsabilidades y funciones..." required></textarea>
              <div class="form-text">Máximo 500 caracteres.</div>
            </div>

            {{-- Resumen de permisos --}}
            <div class="mb-3">
              <label class="form-label d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-primary"></i> Resumen de Permisos
              </label>
              <div class="p-3 bg-light border rounded">
                <div class="d-flex justify-content-between">
                  <span>Permisos seleccionados:</span>
                  <strong id="permSel">0</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Total disponibles:</span>
                  <strong id="permTot">0</strong>
                </div>
              </div>
            </div>

            {{-- Hidden CSV de permisos --}}
            <input type="hidden" name="permisos" id="permisosCSV">

            <div class="d-flex gap-2">
              <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Guardar Rol
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Panel derecho: Asignar Permisos --}}
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-header d-flex justify-content-between align-items-center" style="background:#0dcaf0;color:#083344;">
            <div class="fw-semibold">
              <i class="fas fa-user-shield me-2"></i> Asignar Permisos
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-dark" type="button" id="btnCheckAll">
                <i class="fas fa-check-double me-1"></i> Seleccionar Todo
              </button>
              <button class="btn btn-sm btn-outline-dark" type="button" id="btnUncheckAll">
                <i class="fas fa-eraser me-1"></i> Limpiar Todo
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-3" id="permGrid">

              {{-- Académico --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-book me-2"></i>Académico</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $academico = [
                        'ver_estudiantes_asignados',
                        'ver_matriculas',
                        'registrar_notas',
                        'editar_notas',
                        'gestionar_materias',
                        'gestionar_cursos_y_materias',
                        'gestionar_boletines',
                        'crear_horarios',
                        'generar_reportes_academicos',
                        'aprobar_cambios_de_notas',
                      ];
                    @endphp
                    @foreach($academico as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Disciplina --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-gavel me-2"></i>Disciplina</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $disciplina = [
                        'registrar_reporte_disciplinario',
                        'ver_reportes_disciplinarios',
                        'asignar_sanciones',
                        'revisar_casos_graves',
                        'generar_informes_de_disciplina',
                      ];
                    @endphp
                    @foreach($disciplina as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Asistencia --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-clipboard-check me-2"></i>Asistencia</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $asistencia = [
                        'registrar_asistencias',
                        'ver_registros_de_asistencia',
                        'justificar_inasistencias',
                      ];
                    @endphp
                    @foreach($asistencia as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Horarios --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-clock me-2"></i>Horarios</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $horarios = [
                        'ver_horarios',
                        'editar_horarios',
                      ];
                    @endphp
                    @foreach($horarios as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Finanzas (Tesorería) --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-coins me-2"></i>Finanzas</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $finanzas = [
                        'gestionar_paz_y_salvo',
                        'gestionar_pagos_matriculas',
                        'gestionar_facturacion',
                        'gestionar_devoluciones',
                        'gestionar_carteras',
                        'generar_reportes_financieros',
                        'gestionar_becas_y_descuentos',
                        'consultar_estado_de_cuentas',
                      ];
                    @endphp
                    @foreach($finanzas as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Orientación --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-hand-holding-heart me-2"></i>Orientación</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $orientacion = [
                        'gestionar_citas',
                        'asesorar_estudiantes',
                        'asesorar_docentes_y_acudientes',
                        'registrar_informes_psicologicos',
                        'gestionar_programas_de_orientacion',
                        'gestionar_casos_graves',
                      ];
                    @endphp
                    @foreach($orientacion as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Administración del sistema --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-server me-2"></i>Administración</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $admin = [
                        'gestionar_usuarios',
                        'gestionar_roles_y_permisos',
                        'realizar_copias_de_seguridad',
                        'restaurar_sistema',
                        'monitorear_rendimiento_del_sistema',
                        'actualizar_sistema',
                        'gestionar_informacion_institucional',
                        'consultar_informacion_colegio',
                        'publicar_reglamentos_y_normas',
                        'registrar_informacion_institucional',
                        'aprobar_matriculas',
                        'gestionar_coordinadores',
                        'gestionar_acudientes',
                        'gestionar_orientador',
                        'gestionar_docentes',
                        'gestionar_estudiantes',
                      ];
                    @endphp
                    @foreach($admin as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              {{-- Documentos (ejemplo) --}}
              <div class="col-md-6">
                <div class="border rounded h-100">
                  <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="fas fa-file-alt me-2"></i>Documentos</span>
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-secondary group-select">+</button>
                      <button type="button" class="btn btn-outline-secondary group-clear">–</button>
                    </div>
                  </div>
                  <div class="p-3">
                    @php
                      $docs = [
                        'solicitar_certificados',
                        'subir_documentos',
                        'descargar_documentos',
                        'gestionar_plantillas',
                      ];
                    @endphp
                    @foreach($docs as $p)
                      <div class="form-check">
                        <input class="form-check-input permiso" type="checkbox" value="{{ $p }}" id="p-{{ $p }}">
                        <label class="form-check-label" for="p-{{ $p }}">{{ ucwords(str_replace('_',' ', $p)) }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

            </div> {{-- /row --}}
          </div>
        </div>
      </div>
      {{-- /Panel derecho --}}
    </div>
  </form>
</div>

{{-- JS: manejo de checks, contadores y CSV --}}
<script>
(function(){
  const checks = Array.from(document.querySelectorAll('.permiso'));
  const permSel = document.getElementById('permSel');
  const permTot = document.getElementById('permTot');
  const hiddenCSV = document.getElementById('permisosCSV');
  const btnAll = document.getElementById('btnCheckAll');
  const btnNone = document.getElementById('btnUncheckAll');
  const form = document.getElementById('formRol');

  // total
  permTot.textContent = checks.length;

  function refresh() {
    const selected = checks.filter(c => c.checked).map(c => c.value);
    permSel.textContent = selected.length;
    hiddenCSV.value = selected.join(',');
  }

  // eventos individuales
  checks.forEach(c => c.addEventListener('change', refresh));

  // seleccionar/limpiar todo
  btnAll.addEventListener('click', () => { checks.forEach(c => c.checked = true); refresh(); });
  btnNone.addEventListener('click', () => { checks.forEach(c => c.checked = false); refresh(); });

  // por grupo (cada tarjeta tiene .group-select / .group-clear)
  document.querySelectorAll('#permGrid .border.rounded').forEach(card => {
    const sel = card.querySelector('.group-select');
    const clr = card.querySelector('.group-clear');
    const cardChecks = Array.from(card.querySelectorAll('.permiso'));
    if (sel) sel.addEventListener('click', () => { cardChecks.forEach(c => c.checked = true); refresh(); });
    if (clr) clr.addEventListener('click', () => { cardChecks.forEach(c => c.checked = false); refresh(); });
  });

  // al cargar
  refresh();

  // validar antes de enviar (opcional adicional)
  form.addEventListener('submit', function(e){
    // aquí podrías forzar que al menos un permiso esté marcado si lo deseas
    // if (!hiddenCSV.value) { e.preventDefault(); alert('Selecciona al menos un permiso.'); }
  });
})();
</script>
@endsection

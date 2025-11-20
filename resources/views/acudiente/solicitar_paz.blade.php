@extends('layouts.app')

@section('title', 'Solicitar Paz y Salvo - Acudiente')

@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <h1 class="mb-4">Solicitar Paz y Salvo</h1>

      <div class="card">
        <div class="card-body">
          <form id="solicitudForm" method="POST" enctype="multipart/form-data" action="{{ route('acudiente.crear_solicitud_paz') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Seleccionar hijo</label>
              <select name="estudiante_id" class="form-control" required>
                <option value="">Cargando...</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Adjuntar documento (opcional)</label>
              <input type="file" name="archivo" class="form-control">
            </div>

            <button class="btn btn-primary">Enviar solicitud</button>
          </form>

          <div id="mensaje" class="mt-3"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function cargarHijos() {
  fetch('/api/acudiente/estudiantes')
    .then(r => r.json())
    .then(data => {
      const select = document.querySelector('select[name="estudiante_id"]');
      select.innerHTML = '';
      (data.estudiantes || []).forEach(e => {
        const opt = document.createElement('option');
        opt.value = e.id; opt.text = e.nombre_completo || e.nombre || e.email;
        select.appendChild(opt);
      });
      if (!select.children.length) {
        select.innerHTML = '<option value="">No hay hijos vinculados</option>';
      }
    });
}

document.getElementById('solicitudForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch(form.action, { method: 'POST', body: formData, headers: {'X-CSRF-TOKEN': token } })
    .then(r => r.json())
    .then(json => {
      const msg = document.getElementById('mensaje');
      if (json.success) {
        msg.innerHTML = '<div class="alert alert-success">Solicitud enviada correctamente.</div>';
        form.reset();
      } else {
        msg.innerHTML = '<div class="alert alert-danger">Ocurrió un error: ' + (json.error || 'Verifica los datos') + '</div>';
      }
    }).catch(err => {
      document.getElementById('mensaje').innerHTML = '<div class="alert alert-danger">Error al enviar la solicitud.</div>';
    });
});

cargarHijos();
</script>
@endsection

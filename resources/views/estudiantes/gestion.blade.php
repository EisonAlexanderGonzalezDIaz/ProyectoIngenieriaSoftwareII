{{-- resources/views/estudiantes/gestion.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- =========================
         GESTIÓN DE ESTUDIANTES
    ========================== --}}
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Gestión de Estudiantes</h4>
            <div>
                <button class="btn btn-light btn-sm me-2" onclick="toggleAddStudentForm()">
                    <i class="fas fa-plus"></i> Agregar Estudiante
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="exportStudents()">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- Barra de búsqueda --}}
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchStudents" class="form-control" placeholder="Buscar estudiantes...">
            </div>

            {{-- Tabla de estudiantes --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center bg-white shadow-sm rounded">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>EST001</td>
                            <td>Juan</td>
                            <td>Pérez</td>
                            <td>10°</td>
                            <td>A</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewStudent('EST001')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-outline-warning btn-sm" onclick="editStudent('EST001')"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteStudent('EST001')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>EST002</td>
                            <td>María</td>
                            <td>González</td>
                            <td>9°</td>
                            <td>B</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>EST003</td>
                            <td>Carlos</td>
                            <td>Rodríguez</td>
                            <td>11°</td>
                            <td>C</td>
                            <td><span class="badge bg-secondary">Inactivo</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <nav aria-label="Paginación estudiantes">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>

            {{-- ===========================================
                 FORMULARIO PARA AGREGAR NUEVO ESTUDIANTE
            ============================================ --}}
            <div id="addStudentForm" class="card shadow-sm border-0 mt-5" style="display: none;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus text-success me-2"></i>Agregar Nuevo Estudiante</h5>
                    <button type="button" class="btn-close" onclick="toggleAddStudentForm()"></button>
                </div>
                <div class="card-body">
                    <form id="studentForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="Nombre del estudiante" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" placeholder="Apellido del estudiante" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Grado</label>
                                <select class="form-control" required>
                                    <option value="">Seleccionar grado</option>
                                    @for ($i = 1; $i <= 11; $i++)
                                        <option value="{{ $i }}">{{ $i }}°</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sección</label>
                                <select class="form-control" required>
                                    <option value="">Seleccionar sección</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Género</label>
                                <select class="form-control" required>
                                    <option value="">Seleccionar género</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" placeholder="Dirección del estudiante" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" placeholder="Teléfono del estudiante">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Email del estudiante">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre Acudiente</label>
                            <input type="text" class="form-control" placeholder="Nombre Acudiente">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Teléfono Acudiente</label>
                                <input type="tel" class="form-control" placeholder="Teléfono Acudiente">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Acudiente</label>
                                <input type="email" class="form-control" placeholder="Email Acudiente">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-success" onclick="saveStudent()">
                                <i class="fas fa-save me-1"></i>Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAddStudentForm()">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================
        SCRIPTS PARA FUNCIONALIDAD
===================================== --}}
<script>
    // Mostrar/Ocultar el formulario
    function toggleAddStudentForm() {
        const form = document.getElementById('addStudentForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // Simulación de guardado
    function saveStudent() {
        alert('✅ Estudiante guardado (simulación). Luego se conectará al backend.');
        toggleAddStudentForm();
        document.getElementById('studentForm').reset();
    }

    // Simulación de exportar
    function exportStudents() {
        alert('📁 Exportación simulada. Aquí podrás generar un archivo Excel o PDF.');
    }
</script>
@endsection

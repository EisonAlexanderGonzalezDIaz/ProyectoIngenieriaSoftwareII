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
            {{-- Barra de búsqueda mejorada --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchStudents" class="form-control border-start-0" placeholder="Buscar estudiantes...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm active">Todos</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">Activos</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">Inactivos</button>
                    </div>
                </div>
            </div>

            {{-- Tabla de estudiantes mejorada --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm rounded">
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
                            <td><span class="badge bg-light text-dark">EST001</span></td>
                            <td>Juan</td>
                            <td>Pérez</td>
                            <td>10°</td>
                            <td>A</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewStudent('EST001')" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" onclick="editStudent('EST001')" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteStudent('EST001')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-light text-dark">EST002</span></td>
                            <td>María</td>
                            <td>González</td>
                            <td>9°</td>
                            <td>B</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-light text-dark">EST003</span></td>
                            <td>Carlos</td>
                            <td>Rodríguez</td>
                            <td>11°</td>
                            <td>C</td>
                            <td><span class="badge bg-secondary">Inactivo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Paginación mejorada --}}
            <nav aria-label="Paginación estudiantes">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
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
                                <label class="form-label fw-bold">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" placeholder="Nombre del estudiante" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Apellido</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" placeholder="Apellido del estudiante" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Grado</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                    <select class="form-control" required>
                                        <option value="">Seleccionar grado</option>
                                        @for ($i = 1; $i <= 11; $i++)
                                            <option value="{{ $i }}">{{ $i }}°</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                    <select class="form-control" required>
                                        <option value="">Seleccionar sección</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha de Nacimiento</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Género</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                    <select class="form-control" required>
                                        <option value="">Seleccionar género</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="O">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" placeholder="Dirección del estudiante" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" placeholder="Teléfono del estudiante">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" placeholder="Email del estudiante">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Acudiente</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                <input type="text" class="form-control" placeholder="Nombre Acudiente">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono Acudiente</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                    <input type="tel" class="form-control" placeholder="Teléfono Acudiente">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Acudiente</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                    <input type="email" class="form-control" placeholder="Email Acudiente">
                                </div>
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
    // Mostrar/Ocultar el formulario con animación
    function toggleAddStudentForm() {
        const form = document.getElementById('addStudentForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            form.classList.add('animate__animated', 'animate__fadeIn');
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        } else {
            form.classList.remove('animate__fadeIn');
            form.classList.add('animate__fadeOut');
            setTimeout(() => {
                form.style.display = 'none';
                form.classList.remove('animate__fadeOut');
            }, 500);
        }
    }

    // Simulación de guardado con notificación mejorada
    function saveStudent() {
        // Mostrar notificación en lugar de alert
        showNotification('Estudiante guardado correctamente', 'success');
        
        // Resetear formulario y ocultar
        setTimeout(() => {
            toggleAddStudentForm();
            document.getElementById('studentForm').reset();
        }, 1500);
    }

    // Simulación de exportar con notificación
    function exportStudents() {
        showNotification('Iniciando exportación de datos...', 'info');
    }

    // Función para mostrar notificaciones
    function showNotification(message, type) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="me-3">
                    ${type === 'success' ? '<i class="fas fa-check-circle fa-lg"></i>' : 
                      type === 'info' ? '<i class="fas fa-info-circle fa-lg"></i>' : 
                      '<i class="fas fa-exclamation-circle fa-lg"></i>'}
                </div>
                <div>${message}</div>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        // Agregar al DOM
        document.body.appendChild(notification);
        
        // Eliminar automáticamente después de 5 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
</script>

<!-- Añadir animaciones CSS -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(20px); }
    }
    
    .animate__fadeIn {
        animation: fadeIn 0.5s ease forwards;
    }
    
    .animate__fadeOut {
        animation: fadeOut 0.5s ease forwards;
    }
    
    /* Mejoras visuales */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.075);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .btn-group .btn-sm {
        border-radius: 0.25rem;
        margin: 0 1px;
    }
    
    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.6em;
    }
</style>
@endsection
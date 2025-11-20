# Credenciales de Prueba - Sistema de Administración Escolar

## Resumen de Datos Cargados
- **Usuarios creados**: 18
- **Roles creados**: 9
- **Cursos**: 12
- **Materias**: 20
- **Solicitudes de Beca**: 1 (de prueba)

---

## 🔐 Credenciales de Acceso por Rol

### Administrador
- **Email**: `admin@colegio.edu.co`
- **Contraseña**: `admin123`
- **Permisos**: Acceso total al sistema

### Rector
- **Email**: `rector@colegio.edu.co`
- **Contraseña**: `rector123`
- **Permisos**: Consultar boletines, notas, materias de estudiantes

### Coordinador Académico
- **Email**: `coordinador@colegio.edu.co`
- **Contraseña**: `cooracad123`
- **Permisos**: Gestión de docentes, asignaturas, horarios, recuperaciones

### Coordinador Disciplinario
- **Email**: `coordinadordisciplinario@colegio.edu.co`
- **Contraseña**: `coordisc123`
- **Permisos**: Casos disciplinarios, sanciones, seguimiento

### Orientador
- **Email**: `orientador@colegio.edu.co`
- **Contraseña**: `orientador123`
- **Permisos**: Seguimiento de estudiantes, consejería

### Tesorero
- **Email**: `tesorero@colegio.edu.co`
- **Contraseña**: `tesorero123`
- **Permisos**: Gestión de pagos, reportes financieros

### Docente
- **Email**: `docente@colegio.edu.co`
- **Contraseña**: `docente123`
- **Permisos**: Registrar notas, asistencia, crear tareas, subir materiales

### Acudiente
- **Email**: `acudiente@colegio.edu.co`
- **Contraseña**: `acudiente123`
- **Permisos**: Ver boletines de hijos, solicitar becas, paz y salvo, reportes disciplinarios

### Estudiantes (10 usuarios)
- **Patrón de email**: `estudiante1@colegio.edu.co` a `estudiante10@colegio.edu.co`
- **Contraseña**: `estudiante123` (para todos)
- **Permisos**: Ver notas, boletines, materiales, tareas

---

## 📚 Estructuras Cargadas

### Cursos (12 total)
- Primero de Primaria
- Segundo de Primaria
- Tercero de Primaria
- Cuarto de Primaria
- Quinto de Primaria
- Sexto de Primaria
- Primero de Secundaria
- Segundo de Secundaria
- Tercero de Secundaria
- Cuarto de Secundaria
- Quinto de Secundaria
- Sexto de Secundaria

### Materias Principales (20 total)
- Matemáticas
- Lengua Española
- Inglés
- Ciencias Naturales
- Ciencias Sociales
- Educación Física
- Educación Artística
- Tecnología
- Religión
- Ética y Valores
- Y más...

---

## 🧪 Pruebas Recomendadas

### 1. Flujo Docente
1. Inicia sesión como docente (`docente@colegio.edu.co` / `docente123`)
2. Accede a "Consultar Notas", "Registrar Asistencia"
3. Intenta subir un material académico

### 2. Flujo Acudiente
1. Inicia sesión como acudiente (`acudiente@colegio.edu.co` / `acudiente123`)
2. Visualiza boletines de estudiantes
3. **Solicita una beca** (nueva funcionalidad)
4. Solicita paz y salvo

### 3. Flujo Tesorero
1. Inicia sesión como tesorero
2. Consulta pagos registrados
3. Genera reportes financieros

### 4. Dashboard General
1. Verifica que las estadísticas del dashboard se actualizan cada 30s
2. Comprueba que los botones del sidebar llevan a rutas funcionales

---

## 📝 Notas Importantes

- **Cambiar contraseñas**: Se recomienda cambiar estas credenciales después de la primera prueba
- **Ambiente local**: Estas credenciales son solo para desarrollo/testing
- **Base de datos limpia**: El comando `php artisan migrate:fresh --seed` borra y recrea todos los datos

## 🔄 Comando para Regenerar la BD

```bash
php artisan migrate:fresh --seed
```

---

**Última actualización**: 20/11/2025

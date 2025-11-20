<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\PerformanceEvaluation;
use App\Models\RolesModel;

class CoordinadorAcademicoController extends Controller
{
    /**
     * Mostrar el dashboard del coordinador académico
     */
    public function dashboard()
    {
        return view('coordinador_academico.dashboard');
    }
    
    /**
     * Mostrar la página de gestión de docentes
     */
    public function gestionDocentes()
    {
        // Obtener datos necesarios para la vista
        // Si existe modelo Teacher se usa; si no, recuperamos usuarios con rol Docente
        $subjects = Subject::all();

        if (class_exists(\App\Models\Teacher::class)) {
            $teacherClass = \App\Models\Teacher::class;
            $teachers = $teacherClass::with(['user', 'subjects'])->get();
        } else {
            // Obtener usuarios cuyo rol sea 'Docente'
            $rol = RolesModel::where('nombre', 'Docente')->first();
            if ($rol) {
                $teachers = User::where('roles_id', $rol->id)->get();
            } else {
                $teachers = User::where('roles_id', null)->where('email', 'like', '%@example.com')->limit(0)->get();
            }
        }

        return view('coordinador_academico.gestion_docentes', compact('teachers', 'subjects'));
    }
    
    /**
     * Obtener datos de un docente específico
     */
    public function getTeacherData($id)
    {
        $teacher = Teacher::with(['user', 'subjects', 'performanceEvaluations.evaluator'])->find($id);
        
        if (!$teacher) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }
        
        return response()->json($teacher);
    }
    
    /**
     * Actualizar información de un docente
     */
    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }
        
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'experience' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Actualizar usuario asociado
        $user = $teacher->user;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();
        
        // Actualizar docente
        $teacher->phone = $validated['phone'];
        $teacher->address = $validated['address'];
        $teacher->specialty = $validated['specialty'];
        $teacher->department = $validated['department'];
        $teacher->degree = $validated['degree'];
        $teacher->university = $validated['university'];
        $teacher->experience = $validated['experience'];
        $teacher->status = $validated['status'];
        $teacher->save();
        
        return response()->json(['success' => 'Información actualizada correctamente']);
    }
    
    /**
     * Asignar materias a un docente
     */
    public function assignSubjects(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }
        
        // Validar datos
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);
        
        // Sincronizar materias
        $teacher->subjects()->sync($validated['subjects']);
        
        return response()->json(['success' => 'Materias asignadas correctamente']);
    }
    
    /**
     * Obtener todas las materias (para el modal de asignación)
     */
    public function getAllSubjects()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }
    
    /**
     * Agregar una nueva evaluación de desempeño
     */
    public function addPerformanceEvaluation(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }
        
        // Validar datos
        $validated = $request->validate([
            'period' => 'required|string|max:255',
            'overall_rating' => 'required|numeric|min:0|max:5',
            'categories' => 'required|array',
            'comments' => 'nullable|string',
        ]);
        
        // Crear nueva evaluación
        $evaluation = new PerformanceEvaluation();
        $evaluation->teacher_id = $teacher->id;
        $evaluation->evaluator_id = auth()->id(); // El usuario actual es el evaluador
        $evaluation->period = $validated['period'];
        $evaluation->overall_rating = $validated['overall_rating'];
        $evaluation->categories = $validated['categories'];
        $evaluation->comments = $validated['comments'];
        $evaluation->save();
        
        return response()->json(['success' => 'Evaluación agregada correctamente']);
    }
}
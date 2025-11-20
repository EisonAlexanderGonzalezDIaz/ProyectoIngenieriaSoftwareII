<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanAcademicoController extends Controller
{
    /**
     * Muestra la vista de consulta del plan académico.
     *
     * Ruta sugerida: GET /plan-academico/consultar
     */
    public function gestion()
    {
        // En esta maqueta no enviamos datos dinámicos,
        // solo retornamos la vista estática.
        return view('planacademico.gestion');
    }
}


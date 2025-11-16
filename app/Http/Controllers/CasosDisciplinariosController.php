<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CasosDisciplinariosController extends Controller
{
    /**
     * Mostrar la vista de gestión de casos disciplinarios (solo maqueta).
     */
    public function gestion()
    {
        // Solo devuelve la vista, sin BD todavía
        return view('casos.gestion');
    }
}

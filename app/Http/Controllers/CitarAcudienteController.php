<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitarAcudienteController extends Controller
{
    public function dashboard()
    {
        return view('citas.gestion');
    }
}

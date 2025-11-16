<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrientadorController extends Controller
{
    public function dashboard()
    {
        return view('orientacion.gestion');
    }
}

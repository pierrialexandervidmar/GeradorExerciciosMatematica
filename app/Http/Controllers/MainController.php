<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home() 
    {
        echo 'página inicial';
    }

    public function generateExcercises()
    {
        echo 'gerar exercicios';
    }

    public function printExcercises()
    {
        echo 'imprimir exercicios no navegador';
    }

    public function exportExcercises()
    {
        echo 'exportar exercicios';
    }
}

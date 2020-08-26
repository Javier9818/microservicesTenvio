<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\User;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function getAll(){
        return response()->json(Empresa::getAll());
    }

    public function searchCompanies($clave){
        return response()->json(Empresa::searchCompanies($clave));
    }

}

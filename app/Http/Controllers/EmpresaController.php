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

    public function searchProductsByToken($token){
        return response()->json([
            "productos"=> Empresa::searchProductsByToken($token),
            "info" => Empresa::getEmpresa($token)
        ]);
    }

    public function getTypeDelivery($empresa_id){
        return response()->json([
            "types_delivery"=> Empresa::getTypeDelivery($empresa_id)
        ]);
    }
  
    public function getTypePayments($empresa_id){
        return response()->json([
            "types_payments"=> Empresa::getTypePayments($empresa_id)
        ]);
    }

}

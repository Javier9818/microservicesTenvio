<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
require '../utils/functions.php';

class Empresa extends Model
{

    public static function getAll(){
        return DB::select('SELECT * FROM empresas');
    }

    public static function searchCompanies($clave){
        $empresas =DB::table('empresas')
        ->join('categoria_empresa', 'categoria_empresa.empresa_id', '=', 'empresas.id')
        ->join('categorias', 'categorias.id', '=', 'categoria_empresa.categoria_id')
        ->join('tipo_negocio', 'tipo_negocio.id', '=', 'categorias.tipo_negocio_id')
        ->select('empresas.id','empresas.nombre','empresas.nombre_unico','empresas.descripcion','empresas.foto','tipo_negocio.descripcion as tipo_negocio')
        ->where(
          [
            ['empresas.nombre','like','%'.$clave.'%'],
            ['empresas.estado', '=', 'ACTIVO']
          ])
        ->orWhere( [
            ['categorias.descripcion','like','%'.$clave.'%'],
            ['empresas.estado', '=', 'ACTIVO']
          ])
        ->groupByRaw('empresas.id, empresas.nombre, empresas.nombre_unico, empresas.descripcion, empresas.foto, tipo_negocio.descripcion')
        ->get();

        return $empresas;
    }

    public static function searchProductsByToken($token){
      $productos = DB::table('productos')
      ->join('categorias_menus', 'productos.categorias_menu_id', '=', 'categorias_menus.id')
      ->join('empresas', 'empresas.id', '=', 'productos.empresa_id')
      ->selectRaw('productos.*, categorias_menus.descripcion as categoria')
      ->whereRaw('empresas.token_fb = ? or empresas.id = ? or empresas.nombre_unico = ?', [$token, $token, $token])
      ->get();

      return $productos;
    }

    public static function getEmpresa($id){
      $empresa = DB::table('empresas')->whereRaw('empresas.id = ? or token_fb = ? or nombre_unico', [$id, $id, $id])
              ->join('ciudad', 'ciudad.empresa_id', '=', 'empresas.id')
              ->selectRaw('empresas.*, ciudad.nombre as ciudad')  
              ->get();
      return $empresa;
    }
}

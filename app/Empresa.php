<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
require '../utils/functions.php';

class Empresa extends Model
{

    public static function getAll(){
        return DB::select("SELECT * FROM empresas WHERE estado = 'ACTIVO'");
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
      ->whereRaw('empresas.id_fb = ?  or empresas.id = ? or empresas.token_fb = ? or empresas.nombre_unico = ?', [$token, $token, $token, $token])
      ->get();

      return $productos;
    }

    public static function getEmpresa($id){
      $empresa = DB::table('empresas')->whereRaw('id_fb = ? or empresas.id = ? or token_fb = ? or nombre_unico = ?', [$id, $id, $id, $id])
              ->join('ciudad', 'ciudad.id', '=', 'empresas.ciudad_id')
              ->selectRaw('empresas.*, ciudad.nombre as ciudad')  
              ->get();
      return $empresa;
    }

    public static function getTypeDelivery($empresa_id){
      $empresa = DB::table('empresas')
                ->join('tipo_entrega_empresas', 'tipo_entrega_empresas.empresa_id', '=', 'empresas.id')
                ->join('tipo_entregas', 'tipo_entregas.id', '=', 'tipo_entrega_empresas.tipo_entrega_id')
                ->selectRaw('tipo_entregas.id as tipo_entrega_id, tipo_entregas.nombre as tipo_entrega, empresas.id as empresa_id')
                ->where('empresas.id', '=', $empresa_id)
                ->get();
      return $empresa;
    }

    public static function getTypePayments($empresa_id){
      $tipos = DB::select('select * from tipopago t
                          inner join tipopago_empresa te on te.tipopago_id = t.id
                          where te.empresa_id = ? and te.estado=1', [$empresa_id]);
      return $tipos;
    }
}

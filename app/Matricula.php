<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
require '../utils/functions.php';

class Matricula extends Model
{
    public static function getParams($alumno){
        return DB::select('SELECT * FROM matriculas.sp_parametros_matricula(1, ?)', [$alumno]);
    }

    public static function listCourses($request, $idSede, $alumno){
        $rows = DB::select('SELECT * FROM matriculas.sp_listarcurso_matricula (1, ?, ?, ?, ?, ?)',
            [$request->idperiodo, $idSede, $alumno, $request->idCurricula, $request->seccion]);

        foreach ($rows as $key => $value) {
            $value->ciclo = convertFROMOneDigToTwoDig($value->ciclo);
        }
        return array('total' => count($rows), 'datos' => $rows);
    }

    public static function listTempDetails($request, $alumno){
        $rows = DB::select('SELECT * FROM matriculas.sp_listar_temp_detallematricula (?, ?)', [$request->idperiodo, $alumno]);
        return array('total' => count($rows), 'datos' => $rows);
    }

    public static function addTempDetails($request, $alumno){
        $request = convertUndefinedToNull($request);
        if($request->tipocurso == 'EL'){
            $rows = DB::select('SELECT count(ma.idcurso) as count FROM matriculas.matricula_detalle_temp ma
                                inner join matriculas.curso c on c.idcurso = ma.idcurso and c.cur_tipocurso = 1
                                where ma.idalumno = ? and ma.periodo = ?', [$alumno, $request->idperiodo]);
            if($rows[0]->count != 0) return 2;
        }

        $request->grupoteoprac = ($request->grupoteoprac == 'null') ? $request->grupoteoprac : "'".$request->grupoteoprac."'";
        $request->horarioteoprac = ($request->horarioteoprac == 'null') ? $request->horarioteoprac : "'".$request->horarioteoprac."'";
        $request->grupoteo = ($request->grupoteo == 'null') ? $request->grupoteo : "'".$request->grupoteo."'";
        $request->horarioteo = ($request->horarioteo == 'null') ? $request->horarioteo : "'".$request->horarioteo."'";
        $request->grupoprac = ($request->grupoprac == 'null') ? $request->grupoprac : "'".$request->grupoprac."'";
        $request->horarioprac = ($request->horarioprac == 'null') ? $request->horarioprac : "'".$request->horarioprac."'";
        $request->grupolab = ($request->grupolab == 'null') ? $request->grupolab : "'".$request->grupolab."'";
        $request->horariolab = ($request->horariolab == 'null') ? $request->horariolab : "'".$request->horariolab."'";


        $rows = DB::select("SELECT matriculas.sp_registrar_detallematricula_temp(1, '".$alumno."', ".$request->idcursoprog.",
            '".$request->seccion."', '".$request->periodo."', ".$request->idcurso.", '".($request->curso)."',
            '".($request->tipocurso)."', '".$request->ciclo."', ".$request->idteo.", ". $request->grupoteo.",
            ".($request->horarioteo).", ".$request->idprac.", ".$request->grupoprac.", ".($request->horarioprac).",
            ".$request->idlab.", ".$request->grupolab.", ".($request->horariolab).", '".$request->idteoprac."',
            ".$request->grupoteoprac.", ".($request->horarioteoprac).", '".$request->docente."', '".$request->nrovez."',
            '".$request->creditos."', '', '0', '', 0, '')", []);

        return $rows[0]->sp_registrar_detallematricula_temp;
    }

    public static function deleteTempDetails($request, $alumno){
        $rows = DB::select("SELECT matriculas.sp_registrar_detallematricula_temp(2, '".$alumno."', null, '-', '".$request->periodo."',  ".$request->idcurso.", null, null, null,null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', '', '0', '', 0, '')",
        []);
        return $rows[0]->sp_registrar_detallematricula_temp;
    }

    public static function scheduleCount($request){
        $rows = DB::select("SELECT * FROM matriculas.sp_listateorialaboratorio_matricula_conteo (1, ?)", [$request->idcursoprog]);
        return array('total' => count($rows), 'datos' => $rows);
    }

    public static function loadScheduleTheory($request, $alumno){
        $rows = DB::select("SELECT * FROM matriculas.sp_listateorialaboratorio_matricula (1, ?, ?, ?)", [$request->periodo, $alumno, $request->idcursoprog]);
        array('total' => count($rows), 'HorarioTeoria' => $rows);
    }

    public static function loadSchedulePractice($request, $alumno){
        $rows = DB::select("SELECT * FROM matriculas.sp_listateorialaboratorio_matricula (2, ?, ?, ?)", [$request->periodo, $alumno, $request->idcursoprog]);
        return array('total' => count($rows), 'HorarioPractica' => $rows);
    }

    public static function loadScheduleLab($request, $alumno){
        $rows = DB::select("SELECT * FROM matriculas.sp_listateorialaboratorio_matricula (3, ?, ?, ?)", [$request->periodo, $alumno, $request->idcursoprog]);
        return array('total' => count($rows), 'HorarioLaboratorio' => $rows);
    }

    public static function loadScheduleTheoryAndPractice($request, $alumno){
        $rows = DB::select("SELECT * FROM matriculas.sp_listateorialaboratorio_matricula (4, ?, ?, ?)", [$request->periodo, $alumno, $request->idcursoprog]);
        return array('total' => count($rows), 'HorarioTeoAndPrac' => $rows);
    }

    public static function detailsTempEvent($idcurso, $periodo, $curso){
        $rows = DB::select("SELECT * FROM matriculas.matricula_detalle_temp WHERE periodo=? and idcurso=?;", [$periodo, $idcurso]);
        return (array('total' => count($rows), 'curso' => $curso, 'idcurso' => $idcurso));
    }
}

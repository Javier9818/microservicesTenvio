<?php

namespace App\Http\Controllers;

use App\Matricula;
use App\User;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function getParams(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::getParams($payload->per_codigo));
    }

    public function listCourses(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json( Matricula::listCourses($request, $payload->idsede, $payload->per_codigo));
    }

    public function listTempDetails(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::listTempDetails($request, $payload->per_codigo));
    }

    public function addTempDetails(Request $request){
        $payload = User::payload($request)->getData();
        $res = Matricula::addTempDetails($request, $payload->per_codigo);
        event(new \App\Events\NewMessageEvent(Matricula::detailsTempEvent($request->idcurso, $request->periodo, $request->curso), $payload->idEscuela));
        return response()->json($res);
    }

    public function deleteTempDetails(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::deleteTempDetails($request, $payload->per_codigo));
    }

    public function scheduleCount(Request $request){
        return response()->json(Matricula::scheduleCount($request));
    }

    public function loadScheduleTheory(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::loadScheduleTheory($request, $payload->per_codigo));
    }

    public function loadSchedulePractice(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::loadSchedulePractice($request, $payload->per_codigo));
    }

    public function loadScheduleLab(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::loadScheduleLab($request, $payload->per_codigo));
    }

    public function loadScheduleTheoryAndPractice(Request $request){
        $payload = User::payload($request)->getData();
        return response()->json(Matricula::loadScheduleTheoryAndPractice($request, $payload->per_codigo));
    }

}

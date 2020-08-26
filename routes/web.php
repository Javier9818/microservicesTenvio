<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->get('/empresa', 'EmpresaController@getAll');
$router->get('/empresa/search/{clave}', 'EmpresaController@searchCompanies');



$router->get('/params', 'MatriculaController@getParams');
$router->post('/courses', 'MatriculaController@listCourses');

$router->post('/temporalDetails', 'MatriculaController@listTempDetails');
$router->delete('/temporalDetails', 'MatriculaController@deleteTempDetails');
$router->post('/addTemporalDetails', 'MatriculaController@addTempDetails'); //ESTO TENDRÍA QUE DISPARAR EL EVENTO PARA LA NOTIFICACION

$router->post('/scheduleCount', 'MatriculaController@scheduleCount');
$router->post('/loadScheduleTheory', 'MatriculaController@loadScheduleTheory');
$router->post('/loadSchedulePractice', 'MatriculaController@loadSchedulePractice');
$router->post('/loadScheduleLab', 'MatriculaController@loadScheduleLab');
$router->post('/loadScheduleTheoryAndPractice', 'MatriculaController@loadScheduleTheoryAndPractice');



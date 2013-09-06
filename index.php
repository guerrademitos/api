<?php

/* Función de retorno de la API */
function json($var){
	echo json_encode($var);
}

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

$app->get('/','hello');

$app->run();


/* Implementación de la API */

function hello() {
	$ret = array('message' => "Hello!");
    json($ret);
}




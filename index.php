<?php

$ORDER = "(/orderby/:order(/:direction))";
$PAGER = "(/:limit(/:page))";

/* Implementación de la API */
include 'api.gdm.php';


require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

$app->get('/','hello');
$app->get('/init', 'init');
$app->get('/cards'.$ORDER.$PAGER, 'getCards');


$app->run();





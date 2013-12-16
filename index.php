<?php

$ORDER = "(/orderby/:order(/:direction))";
$PAGER = "(/:limit(/:page))";

/* Implementación de la API */
include 'api.gdm.php';


require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');

$app->get('/','hello');
$app->get('/init', 'init');
$app->get('/cards'.$ORDER.$PAGER, 'getCards');
$app->get('/decks', 'getDecks');
$app->post('/decks', function() use ($app) {
    echo createDeck(json_decode($app->request()->getBody()));
});
$app->options('/(:name+)', function() use ($app) {
    //NANANANANANAN BATMAAAAAAAAAAN
});


$app->run();





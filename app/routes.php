<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//$app->get('/hello/{name}','SlimApp\Controller\HelloController');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction')->add('SlimApp\Controller\Middleware\ExampleMiddleware');
$app->post('/user','SlimApp\Controller\PostUserController');
?>
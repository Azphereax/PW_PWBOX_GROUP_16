<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/hello/{name}','SlimApp\Controller\HelloController')->setName('login');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction')->add('SlimApp\Controller\Middleware\ExampleMiddleware');
//$app->get('/user','SlimApp\Controller\PostUserController');
$app->get('/','SlimApp\Controller\HelloController:access_main_page')->add('SlimApp\Controller\Middleware\SessionMiddleware');
?>
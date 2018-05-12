<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/login','SlimApp\Controller\HelloController:Login')->setName('login');
$app->get('/register','SlimApp\Controller\HelloController:Register')->setName('register');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction');
//$app->get('/hello/{name}','SlimApp\Controller\HelloController:indexAction')->add('SlimApp\Controller\Middleware\ExampleMiddleware');
//$app->post('/user','SlimApp\Controller\PostUserController');
$app->get('/','SlimApp\Controller\HelloController:access_main_page')->add('SlimApp\Controller\Middleware\SessionMiddleware')->setName('main');
$app->post('/save_database','SlimApp\Controller\HelloController:save_database')->setName('save_db');
?>
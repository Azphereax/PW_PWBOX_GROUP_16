<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/login','SlimApp\Controller\HelloController:Login')->setName('login');
$app->get('/register','SlimApp\Controller\HelloController:Register')->setName('register');
$app->post('/connexion','SlimApp\Controller\HelloController:connexion')->setName('connexion')->add('SlimApp\Controller\Middleware\SessionMiddleware:acess_main_from_login');
$app->get('/disconnexion','SlimApp\Controller\HelloController:disconnexion')->setName('disconnexion');
$app->get('/','SlimApp\Controller\HelloController:access_main_page')->add('SlimApp\Controller\Middleware\SessionMiddleware:acess_main')->setName('main');
$app->post('/save_database','SlimApp\Controller\HelloController:save_database')->setName('save_db');
?>
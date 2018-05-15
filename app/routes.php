<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GET
$app->get('/login','SlimApp\Controller\HelloController:Login')->setName('login');
$app->get('/register','SlimApp\Controller\HelloController:Register')->setName('register');
$app->get('/disconnexion','SlimApp\Controller\HelloController:disconnexion')->setName('disconnexion');
$app->get('/','SlimApp\Controller\HelloController:access_main_page')->setName('main');
$app->get('/update_profile','SlimApp\Controller\HelloController:access_update_profile')->setName('update_profile');
$app->get('/remove_account','SlimApp\Controller\HelloController:remove_account')->setName('remove_account');
$app->get('/download_file','SlimApp\Controller\HelloController:download_file')->setName('download_file');
$app->get('/remove_file','SlimApp\Controller\HelloController:remove_file')->setName('remove_file');


//POST 
$app->post('/rename_file','SlimApp\Controller\HelloController:rename_file')->setName('rename_file');
$app->post('/save_database','SlimApp\Controller\HelloController:save_database')->setName('save_db');
$app->post('/connexion','SlimApp\Controller\HelloController:connexion')->setName('connexion');
$app->post('/modify_profile','SlimApp\Controller\HelloController:update_profile')->setName('modify_profile');

?>
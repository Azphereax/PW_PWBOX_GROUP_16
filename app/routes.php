<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/','SlimApp\Controller\access_main')->setName('main');
$app->get('/login','SlimApp\Controller\login')->setName('login');
$app->get('/register','SlimApp\Controller\register')->setName('register');
$app->post('/connexion','SlimApp\Controller\connexion')->setName('connexion');
$app->post('/modify_profile','SlimApp\Controller\update_profile')->setName('modify_profile');
$app->get('/update_profile','SlimApp\Controller\access_update_profile')->setName('update_profile');
$app->get('/remove_account','SlimApp\Controller\remove_account')->setName('remove_account');
$app->get('/download_file','SlimApp\Controller\download_file')->setName('download_file');
$app->get('/disconnexion','SlimApp\Controller\disconnexion')->setName('disconnexion');
$app->post('/rename_file','SlimApp\Controller\rename_file')->setName('rename_file');
$app->post('/save_database','SlimApp\Controller\save_database')->setName('save_db');
$app->get('/remove_file','SlimApp\Controller\remove_file')->setName('remove_file');
$app->post('/create_folder','SlimApp\Controller\create_folder')->setName('create_folder');
$app->post('/upload_file','SlimApp\Controller\upload_file')->setName('upload_file');
$app->post('/share','SlimApp\Controller\share')->setName('share');
$app->post('/shared_folder','SlimApp\Controller\shared_folder')->setName('shared_folder');
$app->get('/shared','SlimApp\Controller\shared')->setName('shared');
?>
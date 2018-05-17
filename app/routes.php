<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/','SlimApp\Controller\access_main')->setName('main');
$app->get('/log','SlimApp\Controller\login')->setName('log');
$app->get('/reg','SlimApp\Controller\register')->setName('reg');
$app->post('/connexion','SlimApp\Controller\connexion')->setName('connexion');
$app->post('/mod','SlimApp\Controller\update_profile')->setName('mod');
$app->get('/update','SlimApp\Controller\access_update_profile')->setName('update');
$app->get('/remove','SlimApp\Controller\remove_account')->setName('remove');
$app->get('/dl','SlimApp\Controller\download_file')->setName('dl');
$app->get('/logout','SlimApp\Controller\disconnexion')->setName('logout');
$app->post('/rename','SlimApp\Controller\rename_file')->setName('rename');
$app->post('/save','SlimApp\Controller\save_database')->setName('save');
$app->get('/file_r','SlimApp\Controller\remove_file')->setName('file_r');
$app->post('/folder_new','SlimApp\Controller\create_folder')->setName('folder_new');
$app->post('/file_up','SlimApp\Controller\upload_file')->setName('file_up');
$app->post('/share','SlimApp\Controller\share')->setName('share');
$app->post('/share_f','SlimApp\Controller\shared_folder')->setName('share_f');
$app->get('/sharing','SlimApp\Controller\shared')->setName('sharing');
?>
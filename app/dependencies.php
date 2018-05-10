<?php
$container = $app->getContainer();
$container['view'] = function($container)
{
		$view= new \Slim\Views\Twig('../src/view/templates',[]); 
		$basePath = rtrim(str_ireplace('index.php','',$container['request']->getUri()->getBasePath()),'/');
		$view->addExtension(new Slim\Views\TwigExtension($container['router'],$basePath));

	return $view;
};
$container['test']=function(){echo 'test';};

$container['doctrine']= function($container)
{
	$config = new \Doctrine\DBAL\Configuration();
	$conn = \Doctrine\DBAL\DriverManager::getConnection($container->get('settings')['database'],$config);
	return $conn;
};

$container['user_repository'] = function($container)
{
	$repository = new \SlimApp\Model\Implementation\DoctrineUserRepository($container->get('doctrine'));
	//echo "</br></br>INFO repo :";print_r($repository);
	return $repository;
};

$container['post_user_use_case'] = function($container)
{	//echo "</br></br>Finale repo :";print_r($container->get('user_repository'));
	$useCase = new \SlimApp\Model\UseCase\PostUserUseCase($container->get('user_repository'));
	return $useCase;
	
};
?>
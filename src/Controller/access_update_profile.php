<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class access_update_profile
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		if(isset($_SESSION['connected'])){
			return $this->container->get('view')->render($response,'update.html',array('name' => $_SESSION['name'],'password' => $_SESSION['password'],'email' => $_SESSION['email'],'birthdate' => $_SESSION['birthdate'],'description' => $_SESSION['description']));
		}
		else
		{
			return $response->withStatus(403)->withHeader('Content-Type','text/html');
		}
	}
}
?>
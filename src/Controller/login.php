<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class login
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		
		$this->container = $container;
		
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		
		return $this->container->get('view')->render($response,'login.html');
		
	}
}
?>
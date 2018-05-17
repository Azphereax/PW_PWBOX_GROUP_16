<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class register
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		
		$this->container = $container;
		
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		try{
			return $this->container->get('view')->render($response,'register.html');
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
		}
		
	}
	
}
?>
<?php

namespace SlimApp\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;


class PostUserController
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
	
	public function __invoke(Request $request,Response $response)
	{
		try
		{
			$data = $request->getParsedBody();
			
			$data['username']="tom";
			$data['email']="tom@tom.fr";
			$data['password']="tommy";
			
			$service= $this->container->get('post_user_use_case');
			if($data)
			{
				$service($data);
			}else
			{
				printf("No data to insert !");
			}
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
		return $response;
	}
}

?>
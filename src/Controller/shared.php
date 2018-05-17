<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class shared
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		try{
			if(isset($_SESSION['connected'])){
				$service= $this->container->get('post_user_use_case');
				$service->shared();
				return $this->container->get('view')->render($response,'shared.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
				
			}
			else
			{
				return $this->container->get('view')->render($response,'landing.html');
			}
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
		}
		
		
	}
}
?>
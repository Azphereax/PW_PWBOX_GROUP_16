<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class remove_account
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		try
		{
			
			if(isset($_SESSION['connected'])){
				$service= $this->container->get('post_user_use_case');
				$service->remove_user();
				session_unset();
				return $this->container->get('view')->render($response,'landing.html');
			}else 
			{
				echo "<script>alert('You should be connected for delete account !')</script>";
				return $response;
			}
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
	}
}
?>
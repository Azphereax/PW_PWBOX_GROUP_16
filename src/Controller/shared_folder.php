<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class shared_folder
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
		
				$data = $request->getParsedBody();
				$bdd=[];
				$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
				$service= $this->container->get('post_user_use_case');
				$service->shared_folder($bdd);
				return $this->container->get('view')->render($response,'shared_folder.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name'],'path' => $_SESSION['path']));		
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
<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class share
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
				$bdd = [];
				
				$bdd['email']=filter_var($data['email'], FILTER_SANITIZE_STRING);
				$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
				
				if($data){
					$service= $this->container->get('post_user_use_case');
					$service->share($bdd);
					return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
				}else return $response;
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
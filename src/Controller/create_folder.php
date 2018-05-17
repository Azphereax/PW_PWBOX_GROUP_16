<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class create_folder
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		
		if(isset($_SESSION['connected'])){
		
				$data = $request->getParsedBody();
				$bdd = [];
				$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
				$bdd['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);	
				if($data){$service= $this->container->get('post_user_use_case');
					$service->create_folder($bdd);
					return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
				}
			}
			else
			{
				return $this->container->get('view')->render($response,'landing.html');
			}
	}
}
?>
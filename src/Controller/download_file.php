<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class download_file
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{

		if(isset($_SESSION['connected'])){	
				if($data=$request->getParam('file'))$get=$data;
				else $get="";
				if(strcmp($get,"")){
					$service= $this->container->get('post_user_use_case');
					$service->download_file($get);	
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
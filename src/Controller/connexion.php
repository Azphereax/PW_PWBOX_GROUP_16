<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class connexion
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
			$data = $request->getParsedBody();
			$bdd = [];
			$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
			$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);	
			$service= $this->container->get('post_user_use_case');
			if(preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/', $bdd['password']) && (strlen($bdd['password'])<=12) && (strlen($bdd['password'])>=6))
			{
				$service->check_user($bdd);
				if(isset($_SESSION['connected']))
					return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
				else {
					echo "<script>alert('Failed connexion');</script>";
					return $this->container->get('view')->render($response,'login.html');
				}
			}else
			{	
				printf("<script>alert('Error in user input.');</script>");
				return $this->container->get('view')->render($response,'login.html');
			}
		
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
		return $response;
	}
}
?>
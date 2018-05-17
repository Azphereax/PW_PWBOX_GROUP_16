<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class update_profile
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
				$data = $request->getParsedBody();
				$bdd = [];
				$bdd['name']=$_SESSION['name'];
				$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
				$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
				$service= $this->container->get('post_user_use_case');
				
				if(((strlen($bdd['password'])<=12) && preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/', $bdd['password'])) || (strlen($bdd['password'])>=6) || strlen($bdd['email']) )
				{
					
					$service->update_user($bdd);
					return $this->container->get('view')->render($response,'update.html',array('name' => $_SESSION['name'],'password' => $_SESSION['password'],'email' => $_SESSION['email'],'birthdate' => $_SESSION['birthdate'],'description' => $_SESSION['description']));
					
				}
				else{
					return $response;
				}
			}
			else 
			{
				return $response;
			}
			
		}catch(\Exception $e)
		{
			
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
	}
	
}
?>
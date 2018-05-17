<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class save_database
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
			$bdd['c_password'] = filter_var($data['c_password'], FILTER_SANITIZE_STRING);	
			$bdd['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
			$bdd['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
			$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
			$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);	
			$bdd['birthdate'] = filter_var($data['birthdate'], FILTER_SANITIZE_STRING);	
			if(preg_match('/(19|20)\d\d[- -.](0[1-9]|[12][0-9]|3[01])[- -.](0[1-9]|1[012])/', $bdd['birthdate']) && preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/', $bdd['password']) && (strlen($bdd['name'])<=20) && (strlen($bdd['password'])>=6) && (strlen($bdd['password'])<=12) && !strcmp($bdd['password'],$bdd['c_password']))
			{
				$service= $this->container->get('post_user_use_case')->save_user($bdd);
				return $this->container->get('view')->render($response,'login.html');
			}else
			{
				printf("<script>alert('Error while save in database.');</script>");
				return $this->container->get('view')->render($response,'register.html');
			}
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
		}
	}
}
?>
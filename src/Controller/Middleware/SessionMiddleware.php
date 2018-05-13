<?php

namespace SlimApp\Controller\Middleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class SessionMiddleware
{
	
    public function __construct($container) {
        $this->container = $container;
	}
	
	public function access_main(Request $request,Response $response,callable $next)
	{
		
		if(isset($_SESSION['user_logged'])){
			
			$next($request,$response);
			return $response;
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function access_main_from_login(Request $request,Response $response,callable $next)
	{
		
		
		$next($request,$response);
		if(isset($_SESSION['user_logged'])){	
			return $this->container->get('view')->render($response,'main_page.html');
		}
		else
		{
			echo "<script>alert('Wrong email/password');</script>";
			return $this->container->get('view')->render($response,'login.html');
		}
	}
}

?>
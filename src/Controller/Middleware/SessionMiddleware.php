<?php

namespace SlimApp\Controller\Middleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class SessionMiddleware
{
	
    public function __construct($container) {
        $this->container = $container;
	}
	
	public function __invoke(Request $request,Response $response,callable $next)
	{
		session_start();
		
		if(isset($_SESSION['user_logged'])){
			
			$next($request,$response);
			return $response;
		}
		else
		{
			//$next($request,$response);
			//return $response;
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
}

?>
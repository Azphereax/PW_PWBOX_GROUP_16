<?php

namespace SlimApp\Controller\Middleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class SessionMiddleware
{
	
    public function __construct($container) {
        $this->container = $container;
	}
	

	

}

?>
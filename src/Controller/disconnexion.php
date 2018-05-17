<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class disconnexion
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		unset($_SESSION['connected']);
		if(session_destroy())echo "<script>alert('Sucessful disconnexion');</script>";
		else echo "<script>alert('Failed disconnexion');</script>";
		return $this->container->get('view')->render($response,'landing.html');
	}
}
?>
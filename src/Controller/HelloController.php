<?php
namespace SlimApp\Controller;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HelloController
{
	protected $container;
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request,Response $response,array $arg)
	{
		echo "Invoke\n";
		$name= $arg['name'];
		$this->container->get('test');
		return $this->container->get('view')->render($response,'hello.twig',['name'=>$name]);
	}
	
	public function indexAction(Request $request,Response $response,array $arg)
	{
		echo "Index Action\n";
		$name= $arg['name'];
		$this->container->get('test');
		return $this->container->get('view')->render($response,'hello.twig',['name'=>$name]);
	}
	
	public function access_main_page(Request $request,Response $response,array $arg)
	{
		return $this->container->get('view')->render($response,'main_page.html');
	}
	
	public function login(Request $request,Response $response,array $arg)
	{
		return $this->container->get('view')->render($response,'login.html');
	}
	
	public function register(Request $request,Response $response,array $arg)
	{
		return $this->container->get('view')->render($response,'register.html');
	}
}
?>
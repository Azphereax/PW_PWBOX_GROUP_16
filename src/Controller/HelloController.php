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

	if(isset($_SESSION['user_logged'])){
	
			$get=".";
			if($data=$request->getParam('folder'))
			$get=$data;
			$service= $this->container->get('post_user_use_case');
			$service->main_access($get);
			return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function login(Request $request,Response $response,array $arg)
	{
		return $this->container->get('view')->render($response,'login.html');
	}
	
	public function register(Request $request,Response $response,array $arg)
	{
		return $this->container->get('view')->render($response,'register.html');
	}
	
	public function save_database(Request $request,Response $response)
	{
		
		try
		{
			$data = $request->getParsedBody();
			$bdd = [];
			$bdd['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
			$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
			$bdd['c_password'] = filter_var($data['c_password'], FILTER_SANITIZE_STRING);	
			$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);	
			$bdd['birthdate'] = filter_var($data['birthdate'], FILTER_SANITIZE_STRING);	
			$bdd['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);	
			
			$date_regex = '/(19|20)\d\d[- -.](0[1-9]|[12][0-9]|3[01])[- -.](0[1-9]|1[012])/';//yyyy-mm-dd
			$pass_regex = '/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/';
			if(!((strlen($bdd['name'])<=20) && (strlen($bdd['password'])>=6) && (strlen($bdd['password'])<=12) && !strcmp($bdd['password'],$bdd['c_password']) && preg_match($date_regex, $bdd['birthdate']) && preg_match($pass_regex, $bdd['password'])  ))
			$data=null;//Do not save , empty the data 
		
			$service= $this->container->get('post_user_use_case');
			if($data)
			{
				$service->save_user($bdd);
				return $this->container->get('view')->render($response,'login.html');
			}else
			{
				
				printf("<script>alert('Error in input.');</script>");
				return $this->container->get('view')->render($response,'register.html');
			}
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
		}
		return $response;
	}
	
	public function connexion(Request $request,Response $response)
	{
		
		try
		{
			$data = $request->getParsedBody();
			$bdd = [];

			$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
			$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
			
			$date_regex = '/(19|20)\d\d[- -.](0[1-9]|[12][0-9]|3[01])[- -.](0[1-9]|1[012])/';//yyyy-mm-dd
			$pass_regex = '/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/';
			
			$service= $this->container->get('post_user_use_case');
			
			if(!((strlen($bdd['password'])>=6) && (strlen($bdd['password'])<=12) && preg_match($pass_regex, $bdd['password'])))
			$data=null;//Do not save , empty the data 
		
			if($data)
			{
				$service->check_user($bdd);
				if(isset($_SESSION['user_logged'])){
					
					return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
				}
				else
				{
					echo "<script>alert('Wrong email/password');</script>";
					return $this->container->get('view')->render($response,'login.html');
				}
				
				
				
			}else
			{
				
				printf("<script>alert('Error in input.');</script>");
				return $this->container->get('view')->render($response,'login.html');
			}
		
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
		return $response;
	}
	
	public function disconnexion(Request $request,Response $response)
	{
		
		unset($_SESSION['user_logged']);
		if(session_destroy())echo "<script>alert('Sucessful disconnexion');</script>";
		else echo "<script>alert('Failed disconnexion');</script>";
		return $this->container->get('view')->render($response,'landing.html');
	}
	
	public function access_update_profile(Request $request,Response $response)
	{
		if(isset($_SESSION['user_logged'])){
			return $this->container->get('view')->render($response,'update.html',array('name' => $_SESSION['name'],'password' => $_SESSION['password'],'email' => $_SESSION['email'],'birthdate' => $_SESSION['birthdate'],'description' => $_SESSION['description']));
		}
		else
		{
			return $response->withStatus(403)->withHeader('Content-Type','text/html');
		}
	}
	
	public function update_profile(Request $request,Response $response)
	{
		
		try
		{
			$data = $request->getParsedBody();
			$bdd = [];

			$bdd['password'] = filter_var($data['password'], FILTER_SANITIZE_STRING);
			$bdd['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
			$bdd['name']=$_SESSION['name'];
			
			$pass_regex = '/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/';
			
			$service= $this->container->get('post_user_use_case');
			
			if(strlen($bdd['email']) || (strlen($bdd['password'])>=6) || ((strlen($bdd['password'])<=12) && preg_match($pass_regex, $bdd['password'])))
			{
				
				$service->update_user($bdd);
			}
			else
				printf("<script>alert('Error in input.');</script>");
			return $this->container->get('view')->render($response,'update.html',array('name' => $_SESSION['name'],'password' => $_SESSION['password'],'email' => $_SESSION['email'],'birthdate' => $_SESSION['birthdate'],'description' => $_SESSION['description']));

		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
	}
	
	public function remove_account(Request $request,Response $response)
	{
		
		try
		{
			$bdd=[];
		
			$bdd['name']=$_SESSION['name'];
			$service= $this->container->get('post_user_use_case');
			$service->remove_user($bdd);
			session_unset();
			return $this->container->get('view')->render($response,'landing.html');
		}catch(\Exception $e)
		{
			$response = $response->withStatus(500)->withHeader('Content-Type','text/html')->write($e->getMessage());
			
		}
	}
	
	public function download_file(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$get="";
			
			if($data=$request->getParam('file'))
			$get=$data;
			if($get!=""){
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
	
	public function remove_file(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$get="";
			
			if($data=$request->getParam('remove'))
			$get=$data;
			if($get!=""){
				$service= $this->container->get('post_user_use_case');
				$service->remove_file($get);
				return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			}
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function rename_file(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$data = $request->getParsedBody();
			$bdd = [];

			$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
			$bdd['o_name'] = filter_var($data['o_name'], FILTER_SANITIZE_STRING);	
			$bdd['new_name'] = filter_var($data['new_name'], FILTER_SANITIZE_STRING);
			
			
			if($data){
				$service= $this->container->get('post_user_use_case');
				$service->rename_file($bdd);
				return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			}
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function create_folder(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$data = $request->getParsedBody();
			$bdd = [];

			$bdd['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);	
			$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
			if($data){
				$service= $this->container->get('post_user_use_case');
				$service->create_folder($bdd);
				return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			}
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function upload_file(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$data = $request->getParsedBody();
			$bdd = [];
			$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
			if($data){
				$service= $this->container->get('post_user_use_case');
				$service->upload_file($bdd);
				return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			}
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function share(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			$data = $request->getParsedBody();
			$bdd = [];
			$bdd['path'] = filter_var($data['path'], FILTER_SANITIZE_STRING);
			$bdd['email']=filter_var($data['email'], FILTER_SANITIZE_STRING);
			if($data){
				$service= $this->container->get('post_user_use_case');
				$service->share($bdd);
				return $this->container->get('view')->render($response,'main_page.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			}
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
	
	public function shared(Request $request,Response $response,array $arg)
	{

	if(isset($_SESSION['user_logged'])){
	
			
			$service= $this->container->get('post_user_use_case');
			$service->shared();
			return $this->container->get('view')->render($response,'shared.html',array('content' => $_SESSION['content'],'name' => $_SESSION['name']));
			
		}
		else
		{
			return $this->container->get('view')->render($response,'landing.html');
		}
	}
}
?>
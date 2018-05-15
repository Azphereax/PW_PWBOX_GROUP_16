<?php

namespace SlimApp\Model\Implementation;

use SlimApp\Model\User;
use SlimApp\Model\UserRepository;
use Doctrine\DBAL\Connection;

class DoctrineUserRepository implements UserRepository
{
	private $database;
	
	public function __construct(Connection $database)
	{
		$this->database = $database;
	}
	
	public function save(User $user)
	{
		
		$sql = "INSERT INTO users(name,email,password,birthdate,description) VALUES(:name,:email,:password,:birthdate,:description)";
		$sql_2="SELECT COUNT(*) AS unique_name from users where name=:name && email=:email";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("name",$user->getName(),'string');
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		$stmt->bindValue("birthdate",$user->getBirthdate(),'string');
		$stmt->bindValue("description",$user->getDescription(),'string');
		
		$stmt_2->bindValue("name",$user->getName(),'string');
		$stmt_2->bindValue("email",$user->getEmail(),'string');
		$stmt_2->execute();
		$result=$stmt_2->fetch();

		
		if(!$result['unique_name']){
		if($stmt->execute()){
			if (!file_exists('../Cloud_user')) {
			mkdir('../Cloud_user', 0777, true);
			}
			
			if (!file_exists('../Cloud_user/'.$user->getName())) {
			if(mkdir('../Cloud_user/'.$user->getName(), 0777, true))printf("<script>alert('User and Storage successfully created.')</script>");;
			}
			}else
			{
				printf("<script>alert('User not successfully created.')</script>");
			}
		}
		else
			printf("<script>alert('User/email already exist.')</script>");
			
	}
	
	public function check(User $user)
	{
		
		$sql="SELECT COUNT(*) AS found_user from users where password=:password and email=:email";
		$sql_2="SELECT * from users where password=:password and email=:email";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		
		$stmt_2->bindValue("email",$user->getEmail(),'string');
		$stmt_2->bindValue("password",$user->getPassword(),'string');
		
		$stmt->execute();
		$result=$stmt->fetch();
		
		if($result['found_user']==1){
			
			$stmt_2->execute();
			$result_2=$stmt_2->fetch();
			$_SESSION['user_logged']=1;
			$_SESSION['name']=$result_2['name'];
			$_SESSION['email']=$result_2['email'];
			$_SESSION['birthdate']=$result_2['birthdate'];
			$_SESSION['description']=$result_2['description'];
			$_SESSION['password']=$result_2['password'];
		}
		
		
	}
	
	public function update(User $user)
	{
		$email=$user->getEmail();
		$password=$user->getPassword();
	
		
		if(!strlen($email))
			$sql = "Update users set password=:password where name=:name";
		else if(!strlen($password))
			$sql = "Update users set email=:email where name=:name";
		else
			$sql = "Update users set email=:email,password=:password where name=:name";
		
		$stmt = $this->database->prepare($sql);
		
		$stmt->bindValue("name",$user->getName(),'string');
		if(strlen($email))$stmt->bindValue("email",$email,'string');
		if(strlen($password))$stmt->bindValue("password",$password,'string');
		
		if($stmt->execute())
			if(strlen($email))$_SESSION['email']=$email;
		else
			printf("<script>alert('Failed update')</script>");
			
	}
	
	public function remove(User $user)
	{
		$name=$user->getName();
		$sql = "Delete from users where name=:name";
		
		$stmt = $this->database->prepare($sql);
		
		$stmt->bindValue("name",$user->getName(),'string');
		
		
		if(!$stmt->execute())
			printf("<script>alert('Failed delete users')</script>");
		else
			printf("<script>alert('".$name." account deleted')</script>");
	}
}



?>
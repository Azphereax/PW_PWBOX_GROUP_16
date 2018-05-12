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
		$sql_2="SELECT COUNT(*) AS unique_name from users where name=:name";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("name",$user->getName(),'string');
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		$stmt->bindValue("birthdate",$user->getBirthdate(),'string');
		$stmt->bindValue("description",$user->getDescription(),'string');
		
		$stmt_2->bindValue("name",$user->getName(),'string');
		$stmt_2->execute();
		$result=$stmt_2->fetch();

		echo $result['unique_name'];
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
			printf("<script>alert('User already exist.')</script>");
			
	}
}



?>
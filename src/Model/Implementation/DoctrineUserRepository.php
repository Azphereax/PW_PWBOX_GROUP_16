<?php
namespace SlimApp\Model\Implementation;

use SlimApp\Model\User;
use SlimApp\Model\UserRepository;
use Doctrine\DBAL\Connection;

class DoctrineUserRepository implements UserRepository
{
	//TEST
	private $x = "AA";
	public function getx(){return $this->x;}
	//TEST
	
	private const DATE_FORMAT ='Y-m-d H:i:s';
	
	private $database;
	
	public function __construct(Connection $database)
	{
		$this->database = $database;
	}
	
	public function save(User $user)
	{
		$sql = "INSERT INTO user(username,email,password,create_at,update_at) VALUES(:username,:email,:password,:create_at,:update_at)";
		$stmt = $this->database->prepare($sql);
		$stmt->bindValue("username",$user->getUsername(),'string');
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		$stmt->bindValue("create_at",$user->getCreateAt()->format(self::DATE_FORMAT));
		$stmt->bindValue("update_at",$user->getUpdateAt()->format(self::DATE_FORMAT));
		/*$stmt->execute();*/printf("Have been save .");
	}
}



?>
<?php
namespace SlimApp\Model;
class User
{
private $id;
private $email;
private $username;
private $password;
private $createAt;
private	$updateAt;

public function __construct($id,$username,$email,$password,$createAt,$updateAt)
{
	$this->id= $id;
	$this->username= $username;
	$this->email= $email;
	$this->password= $password;
	$this->createAt= $createAt;
	$this->updateAt= $updateAt;

	
}


public function getId(): int{return $this->id;}
public function getUsername(): string{return $this->username;}
public function getPassword(): string{return $this->password;}
public function getEmail(): string{return $this->email;}
public function getCreateAt(): \Datetime{return $this->createAt;}
public function getUpdateAt(): \Datetime{return $this->updateAt;}

}


?>
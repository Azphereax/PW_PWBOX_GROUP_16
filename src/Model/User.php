<?php
namespace SlimApp\Model;
class User
{

private $birthdate;
private $email;
private	$description;
private $username;
private $password;
private $id;

public function __construct($id,$name,$email,$password,$birthdate,$description)
{
	$this->name= $name;
	$this->birthdate= $birthdate;
	$this->description= $description;
	$this->id= $id;
	$this->email= $email;
	if($password!=null)
	$this->password=hash("md5",$password);
	else $this->password="";
}


public function getDescription(): string{return $this->description;}
public function getId(): int{return $this->id;}
public function getEmail(): string{return $this->email;}
public function getBirthdate(): string{return $this->birthdate;}
public function getName(): string{return $this->name;}
public function getPassword(): string{return $this->password;}

}


?>
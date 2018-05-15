<?php
namespace SlimApp\Model;
class User
{
private $id;
private $email;
private $username;
private $password;
private $birthdate;
private	$description;


public function __construct($id,$name,$email,$password,$birthdate,$description)
{
	$this->id= $id;
	$this->name= $name;
	$this->email= $email;
	if($password!=null)
	$this->password=hash("sha256",$password);
	else $this->password="";
	$this->birthdate= $birthdate;
	$this->description= $description;

}


public function getId(): int{return $this->id;}
public function getName(): string{return $this->name;}
public function getPassword(): string{return $this->password;}
public function getEmail(): string{return $this->email;}
public function getBirthdate(): string{return $this->birthdate;}
public function getDescription(): string{return $this->description;}

}


?>
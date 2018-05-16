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
			$_SESSION['path']="../Cloud_user/".$_SESSION['name'];
			$this->main_access(".");
			
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
	
	public function DeleteDirectory($path_dir) {
		$dfiles = glob($path_dir . '/*');
		foreach ($dfiles as $file) {
			is_dir($file) ? $this->DeleteDirectory($file) : unlink($file);
		}	
		return @rmdir($path_dir);
	}

	public function remove(User $user)
	{
		$sql =  "Delete from users where name=:name;";
		$sql_2= "Delete from folders where owner=:owner;";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("name",$_SESSION["name"],'string');
		$stmt_2->bindValue("owner",$_SESSION["name"],'string');
		
		$this->DeleteDirectory("../Cloud_user/".$_SESSION["name"]);
		
		$stmt->execute();
		$stmt_2->execute();
		$this->DeleteDirectory("../Cloud_user/".$_SESSION["name"]);
		printf("<script>alert('".$_SESSION["name"]." account deleted')</script>");
		
		
	}
	
	public function save_content($path,$content){
		if($d=@dir($path)){
			while (($file = $d->read()) !== false){
			
			if(!strcmp($file,".") || !strcmp($file,".."))
				array_push($content,array($file,"s",getcwd()."/".rtrim($path,'.')));
			else array_push($content,array($file,(is_dir($path."/".$file))?"Folder":"File",getcwd()."/".rtrim($path,'.')));
			}
			$d->close(); 

		}
		else
			echo "<script>alert('Failed open folder".$path."')</script>";
		return $content;
	}
	
	
	public function main_access($path_access){
		
			$content=[];
			if($path_access==".")$_SESSION['path']="../Cloud_user/".$_SESSION['name'];
			$_SESSION['path'].="/".$path_access;
			$content=$this->save_content($_SESSION['path'],$content);
			$_SESSION['content']=$content;
	}
	
	public function rename_file($data){

	if(file_exists($data['path']."/".$data['o_name'])) {
					if(!rename($data['path']."/".$data['o_name'],$data['path']."/".$data['new_name']))echo "<script>alert('Failed rename .');</script>";
					else echo "<script>alert('Sucessfully rename');</script>";
			}else echo "<script>alert('Failed rename .');</script>";
			
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
	
	public function download_file($path_access){
			
			echo $path_access;
			if(file_exists($path_access)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($path_access).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($path_access));
				flush(); 
				readfile($path_access);
			}
    }
	
	public function remove_file($path_access){
			
			
			if(file_exists($path_access)) {
				if(is_dir ($path_access))
					if(!@rmdir($path_access))echo "<script>alert('Failed delete (Not empty) .');</script>";
					else echo "<script>alert('Sucessfully deleted');</script>";
				else
					if(!unlink($path_access))echo "<script>alert('Failed delete .');</script>";
					else echo "<script>alert('Sucessfully deleted');</script>";
			}
			
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
		
	public function create_folder($data){
			if(@	mkdir($data['path']."/".$data['name']))
				echo "<script>alert('Successful created folder');</script>";
			else
				echo "<script>alert('Failed created folder (no right or already exists)');</script>";		
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
	
	public function upload_file($data){
		$path_file=$data['path']."/".$_FILES['upload_file']['name'];
		$ext=strtolower(pathinfo($path_file,PATHINFO_EXTENSION));
		if($ext!="pdf" && $ext!="jpg" && $ext!="png" && $ext!="gif" && $ext!="md" && $ext!="txt")
			echo "<script>alert('bad file extension.')</script>";
		else if (file_exists($path_file)){
			echo "<script>alert('file already exists.')</script>";
		}else if ($_FILES["upload_file"]["size"] > 2000000) {
			echo "<script>alert('file too large.')</script>";
		}else if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path_file)) {
			echo "<script>alert('Successful uploaded.')</script>";
		}
		else {
			echo "<script>alert('Failed uploaded.')</script>";
		}
		
		$content=[];
		$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
	
	public function share($data)
	{
		$sql="SELECT COUNT(*) AS found_user from users where email=:email and name!=:name";
		$sql_2 = "INSERT INTO folders(Folders,owner,user_share,role) VALUES(:folders,:owner,:user_share,:role)";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("email",$data['email'],'string');
		$stmt->bindValue("name",$_SESSION['name'],'string');
		
		$stmt_2->bindValue("folders",$data['path'],'string');
		$stmt_2->bindValue("owner",$_SESSION['name'],'string');
		$stmt_2->bindValue("user_share",$data['email'],'string');
		$stmt_2->bindValue("role","reader",'string');		
		
		$stmt->execute();
		$result=$stmt->fetch();
		
		if($result['found_user']==1){
			
		if($stmt_2->execute())echo "<script>alert('Sucessfully share user.')</script>";
		else echo "<script>alert('Failed share user.')</script>";
			
		}else echo "<script>alert('Users already share/owner or not found.')</script>";
	}
	
	public function shared()
	{
		$sql="SELECT * from folders where user_share=:email";
		$stmt = $this->database->prepare($sql);
		$stmt->bindValue("email",$_SESSION['email'],'string');
		$stmt->execute();
		$content=[];
		while($result=$stmt->fetch())array_push($content,array($result['Folders'],$result['owner'],$result['user_share'],$result['role']));
		$_SESSION['content']=$content;
	}
	
	public function shared_folder($data)
	{
		$content=[];
		$content=$this->save_content($data['path'],$content);
		$_SESSION['content']=$content;
		$_SESSION['path']=$data['path'];
	}
	
}



?>
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
	
	public function DeleteDirectory($dir) 
	{
		$the_files = glob($dir . '/*');
		
		foreach ($the_files as $file_to_remove) 
		{
			if(is_dir($file_to_remove))
			{
				$this->DeleteDirectory($file_to_remove);
			}
			else
			{
				unlink($file_to_remove);
			}
		}	
		return @rmdir($dir);
	}

	public function save_content($chemin,$cont){
		
		$value=@dir($chemin);
		
		if($value)
		{
			while (($fichier = $value->read())!== false){
			
			if(!strcmp($fichier,".") || !strcmp($fichier,".."))
				array_push($cont,array($fichier,"dot",getcwd()."/".rtrim($chemin,'.')));
			else array_push($cont,array($fichier,(is_dir($chemin."/".$fichier))?"Folder":"File",getcwd()."/".rtrim($chemin,'.')));
			}
			$value->close();
		}
		else
			echo "<script>alert('Failed open folder".$chemin."')</script>";
		return $cont;
	}
	
	public function remove()
	{
		$sql =  "Delete from users where name=:name;";
		$stmt = $this->database->prepare($sql);
		$stmt->bindValue("name",$_SESSION["name"],'string');
		
		
		$sql_2= "Delete from folders where owner=:owner;";
		$stmt_2 = $this->database->prepare($sql_2);
		$stmt_2->bindValue("owner",$_SESSION["name"],'string');
		
		$this->DeleteDirectory("../Cloud_user/".$_SESSION["name"]);
		
		$stmt->execute();
		$stmt_2->execute();
		$this->DeleteDirectory("../Cloud_user/".$_SESSION["name"]);
		printf("<script>alert('".$_SESSION["name"]." account deleted')</script>");	
	}
	
	public function DirectorySize ($path)
	{
		$size = 0;
		foreach (glob(rtrim($path, '/').'/*', GLOB_NOSORT) as $inst) 
		{
			if(is_file($inst)){	
				$size +=filesize($inst);
			}else
			{
				$size +=$this->DirectorySize($inst);
			}
			
		}
		return $size;
	}
	
	
	public function check(User $user)
	{
		
		$sql="SELECT COUNT(*) AS found_user from users where password=:password and email=:email";
		$stmt = $this->database->prepare($sql);
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		
		$sql_2="SELECT * from users where password=:password and email=:email";
		$stmt_2 = $this->database->prepare($sql_2);
		$stmt_2->bindValue("email",$user->getEmail(),'string');
		$stmt_2->bindValue("password",$user->getPassword(),'string');
		$stmt->execute();
		if($stmt->fetch()['found_user']){
			$stmt_2->execute();
			$result_2=$stmt_2->fetch();

			$_SESSION['birthdate']=$result_2['birthdate'];
			$_SESSION['description']=$result_2['description'];
			$_SESSION['name']=$result_2['name'];
			$_SESSION['connected']=1;
			$_SESSION['path']="../Cloud_user/".$_SESSION['name'];
			$_SESSION['email']=$result_2['email'];
			$_SESSION['password']=$result_2['password'];
			$this->main_access(".");
			
		}
	}
	
	public function update(User $user)
	{
		
		$password=$user->getPassword();
		
		$email=$user->getEmail();
		
		if(!strlen($password))$sql = "Update users set email=:email where name=:name";
		else if(!strlen($email))$sql = "Update users set password=:password where name=:name";
		else$sql = "Update users set email=:email,password=:password where name=:name";
		
		$stmt = $this->database->prepare($sql);
		
		$stmt->bindValue("name",$user->getName(),'string');
		
		if(strlen($password))$stmt->bindValue("password",$password,'string');
		if(strlen($email))$stmt->bindValue("email",$email,'string');
		
		
		if($stmt->execute())
			if(strlen($email))$_SESSION['email']=$email;
		else
			printf("<script>alert('Failed update')</script>");
		
	}
	
	public function main_access($the_path){
		
			$content=[];
			if($the_path==".")$_SESSION['path']="../Cloud_user/".$_SESSION['name'];
			$_SESSION['path']=$_SESSION['path']."/".$the_path;
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
	}
	
	public function save(User $user)
	{
		
		$sql = "INSERT INTO users(name,email,password,birthdate,description) VALUES(:name,:email,:password,:birthdate,:description)";
		$stmt = $this->database->prepare($sql);
		$stmt->bindValue("name",$user->getName(),'string');
		$stmt->bindValue("email",$user->getEmail(),'string');
		$stmt->bindValue("password",$user->getPassword(),'string');
		$stmt->bindValue("birthdate",$user->getBirthdate(),'string');
		$stmt->bindValue("description",$user->getDescription(),'string');
		
		$sql_2="SELECT COUNT(*) AS u_name from users where name=:name && email=:email";
		$stmt_2 = $this->database->prepare($sql_2);
		$stmt_2->bindValue("name",$user->getName(),'string');
		$stmt_2->bindValue("email",$user->getEmail(),'string');

		$stmt_2->execute();
		if(!$stmt_2->fetch()['u_name']){
		if($stmt->execute()){
			if (!file_exists('../Cloud_user'))mkdir('../Cloud_user', 0777, true);
			if (!file_exists('../Cloud_user/'.$user->getName()))if(mkdir('../Cloud_user/'.$user->getName(), 0777, true))printf("<script>alert('User and Storage successfully created.')</script>");;
			}else printf("<script>alert('User not successfully created.')</script>");
		}
		else
			printf("<script>alert('User/email already exist.')</script>");
			
	}
	
	public function rename_file($file)
	{
		if(file_exists($file['path']."/".$file['o_name']) && strlen($file['new_name'])>0 && $file['new_name']!='null') {
			if(!rename($file['path']."/".$file['o_name'],$file['path']."/".$file['new_name']))echo "<script>alert('Failed rename .');</script>";
			else echo "<script>alert('Sucessfully rename');</script>";
			$cont=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$cont);
		}else echo "<script>alert('Failed rename .');</script>";
			
    }
	
	public function download_file($the_path)
	{
			if(file_exists($the_path)) 
			{
				//Content description
				header('Content-Description: File Transfer');
				//Content-Type
				header('Content-Type: application/octet-stream');
				//Content-Disposition
				header('Content-Disposition: attachment; filename="'.basename($the_path).'"');
				//Expires
				header('Expires: 0');
				//Cache-Control
				header('Cache-Control: must-revalidate');
				//Pragma
				header('Pragma: public');
				//Content-Length
				header('Content-Length: ' . filesize($the_path));flush(); 
				
				
				readfile($the_path);
			}
    }
	
	public function share($value)
	{
		$sql="SELECT COUNT(*) AS found_user from users where email=:email and name!=:name";
		$sql_2 = "INSERT INTO folders(Folders,owner,user_share,role) VALUES(:folders,:owner,:user_share,:role)";
		
		$stmt = $this->database->prepare($sql);
		$stmt_2 = $this->database->prepare($sql_2);
		
		$stmt->bindValue("email",$value['email'],'string');
		$stmt->bindValue("name",$_SESSION['name'],'string');
		
		$stmt_2->bindValue("folders",$value['path'],'string');
		$stmt_2->bindValue("owner",$_SESSION['name'],'string');
		$stmt_2->bindValue("user_share",$value['email'],'string');
		$stmt_2->bindValue("role","reader",'string');		
		
		$stmt->execute();
		$result=$stmt->fetch();
		
		if($result['found_user']==1){
			
		if($stmt_2->execute())echo "<script>alert('Sucessfully share user.')</script>";
		else echo "<script>alert('Failed share user.')</script>";
			
		}else echo "<script>alert('Users already share/owner or not found.')</script>";
	}
	
	public function remove_file($the_path){
			
			
			if(file_exists($the_path)) {
				if(is_dir ($the_path))
					if(!@rmdir($the_path))echo "<script>alert('Failed delete (Not empty) .');</script>";
					else echo "<script>alert('Sucessfully deleted');</script>";
				else
					if(!unlink($the_path))echo "<script>alert('Failed delete .');</script>";
					else echo "<script>alert('Sucessfully deleted');</script>";
			}
			
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
		
	public function create_folder($value){
			if(@mkdir($value['path']."/".$value['name']))
				echo "<script>alert('Successful created folder');</script>";
			else
				echo "<script>alert('Failed created folder (no right or already exists)');</script>";		
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
    }
	
	
	
	public function shared_folder($value)
	{
		$content=[];
		$_SESSION['content']=$this->save_content($value['path'],$content);
		$_SESSION['path']=$value['path'];
	}
	
	public function upload_file($value){
		
		$size_of_the_file=$_FILES["file_up"]["size"];
		
		$size_of_the_directory=$this->DirectorySize("../Cloud_user/".$_SESSION['name']."/");
		
		if(($size_of_the_directory+$size_of_the_file)<pow(10,9))
		{
			$path_file=$value['path']."/".$_FILES['file_up']['name'];
			$ext=strtolower(pathinfo($path_file,PATHINFO_EXTENSION));
			if($ext!="pdf" && $ext!="jpg" && $ext!="png" && $ext!="gif" && $ext!="md" && $ext!="txt")echo "<script>alert('bad file extension.')</script>";
			else if (file_exists($path_file))echo "<script>alert('file already exists.')</script>";
			else if ($size_of_the_file > 2*pow(10,6))echo "<script>alert('file too large.')</script>";
			else if (move_uploaded_file($_FILES["file_up"]["tmp_name"], $path_file))echo "<script>alert('Successful uploaded. Left Cloud place = ".((pow(10,9)-($size_of_the_directory+$size_of_the_file))/pow(10,6))." Mo.')</script>";
			else echo "<script>alert('Failed uploaded.')</script>";
			$content=[];
			$_SESSION['content']=$this->save_content($_SESSION['path'],$content);
		}else echo "<script>alert('Size limit reached ,only ".(pow(10,9)-($size_of_the_directory+$size_of_the_file))."available')</script>";
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
}
?>
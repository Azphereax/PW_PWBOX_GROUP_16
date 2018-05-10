<?php

namespace SallePW\Model;
class MySQLTaskRepository implements TaskRepository
{
    public function save(Task $task)
	{
		//To_Do_SQL_call_$task
		$date = (new \DateTime())->format('Y-m-d-H-i-s');
		$task->setCreatedAt($date);
		$task->setUpdatedAt($date);
		$title=$task->getTitle();
		$content=$task->getContent();
		
		if($title!="" && $content!="" && strlen($title)<20)
		{
			
			try
			{
				//Connexion
				$bdd = new \PDO('mysql:host=localhost;dbname=homestead;charset=utf8','homestead','secret');	
			}
			catch (Exception $e)
			{
					die('Erreur : ' . $e->getMessage());
			}
					
			
			$req = $bdd->prepare('insert into task(title,content,created_at,updated_at) values(:title, :content, :created_at, :updated_at)');
			if($req->execute(array('title' => $title,'content' => $content ,'created_at' => $task->getCreatedAt(),'updated_at' => $task->getUpdatedAt())))
				echo "<script>alert(\"Sucessful Add\")</script>";
			else echo "<script>function disp(){alert(\"Failed Add\");document.getElementsByTagName(\"input\")[0].value=\"".$title."\";document.getElementsByTagName(\"input\")[1].value=\"".$content."\";}window.onload = disp;</script>";

				
		}else
				echo "<script>alert(\"Failed Add ,Invalid input : empty or title bigger not less than 20 letters .\");function disp(){document.getElementsByTagName(\"input\")[0].value=\"".$title."\";document.getElementsByTagName(\"input\")[1].value=\"".$content."\";}window.onload = disp;</script>";
			
		}
}

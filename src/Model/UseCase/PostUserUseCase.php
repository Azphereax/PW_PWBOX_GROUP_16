<?php

namespace SlimApp\Model\UseCase;
use SlimApp\Model\User;
use SlimApp\Model\UserRepository;

class PostUserUseCase
{
	private $repo;
	
	public function __construct(UserRepository $repo)
	{
		$this->repo = $repo;
	}
	
	public function save_user(array $data_to_save)
	{
		$user=new User(null,$data_to_save['name'],$data_to_save['email'],$data_to_save['password'],$data_to_save['birthdate'],$data_to_save['description']);
		$this->repo->save($user);
	}
	
	public function check_user(array $user_check)
	{
		$user=new User(null,null,$user_check['email'],$user_check['password'],null,null);
		$this->repo->check($user);
		
	}
}



?>
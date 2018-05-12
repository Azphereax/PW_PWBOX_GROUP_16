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
	
	public function __invoke(array $data_to_save)
	{
		
		$user=new User(null,$data_to_save['name'],$data_to_save['email'],$data_to_save['password'],$data_to_save['birthdate'],$data_to_save['description']);
		$this->repo->save($user);
	}
	
}



?>
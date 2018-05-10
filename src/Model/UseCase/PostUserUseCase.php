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
	
	public function __invoke(array $rawData)
	{
		$now = new \DateTime('now');
		$user=new User(null,$rawData['username'],$rawData['email'],$rawData['password'],$now,$now);
		$this->repo->save($user);
	}
	
}



?>
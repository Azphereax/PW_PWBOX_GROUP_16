<?php

namespace SlimApp\Model;


interface UserRepository
{
	public function save(User $user);
	public function check(User $user);
	public function update(User $user);
}



?>
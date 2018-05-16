<?php

namespace SlimApp\Model;


interface UserRepository
{
	public function save(User $user);
	public function check(User $user);
	public function update(User $user);
	public function remove(User $user);
	public function DeleteDirectory($path);
	public function download_file($path);
	public function remove_file($path);
	public function rename_file($data);
	public function create_folder($data);
	public function upload_file($data);	
	public function share($data);
	public function shared();	
}



?>
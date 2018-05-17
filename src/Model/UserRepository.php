<?php

namespace SlimApp\Model;


interface UserRepository
{
	public function remove();
	public function create_folder($data);
	public function upload_file($data);	
	public function share($data);
	public function update(User $user);
	public function DeleteDirectory($path);
	public function download_file($path);
	public function shared();	
	public function shared_folder($data);
	public function remove_file($path);
	public function rename_file($data);
	public function save(User $user);
	public function check(User $user);


}



?>
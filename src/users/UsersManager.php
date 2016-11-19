<?php

namespace FlatFileCMS\Users;
use \Symfony\Component\Yaml\Yaml;

class UsersManager {

	private $conf;
	private $users_dir;

	public function __construct($conf) {
		$this->conf = $conf;
		$this->users_dir = $this->conf["users_dir"];
	}

	public function write($user) {
		$to_convert = array(
			"username"=>$user->username,
			"password"=>$user->password,
			"encryption"=>$user->encryption,
			"email"=>$user->email,
			"role"=>$user->role,
			"metas"=>$user->metas
		);
		file_put_contents($this->users_dir."/".$user->username.".yml", Yaml::dump($to_convert));
	}

	public function read($id) {
		$user_yml = Yaml::parse(file_get_contents($this->users_dir."/".$id.".yml"));
		$user = new User();
		$user->username = $user_yml["username"];
		$user->password = $user_yml["password"];
		$user->encryption = $user_yml["encryption"];
		$user->email = $user_yml["email"];
		$user->role = $user_yml["role"];
		$user->metas = $user_yml["metas"];
		return $user;
	}

	public function delete($id) {
		unlink($this->users_dir."/".$id.".yml");
	}

	public function edit($user) {
		$this->delete($user->username);
		$this->write($user);
	}

}
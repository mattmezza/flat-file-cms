<?php

namespace FlatFileCMS\Users;
use \Symfony\Component\Yaml\Yaml;
use \FlatFileCMS\Exceptions\ContentNotFound;

class Users {

	private $conf;
	private $dir;

	public function __construct($conf) {
		$this->conf = $conf;
		$this->dir = rtrim($this->conf["users.dir"], "/");
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
		file_put_contents($this->dir."/".$user->username.".yml", Yaml::dump($to_convert));
	}

	public function read($id) {
		$filename = $this->dir."/".$id.".yml";
		if(!file_exists($filename))
			throw new ContentNotFound($id, $filename, ContentNotFound::USER);
		$user_yml = Yaml::parse(file_get_contents($filename));
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
		return unlink($this->dir."/".$id.".yml");
	}

	public function edit($user) {
		if($this->delete($user->username))
			$this->write($user);
	}

	public function list_all($reverse = true) {
		if ($reverse)
      return array_reverse(glob($this->dir . "/" . "*.yml"));
    else
      return glob($this->dir . "/" . "*.yml");
	}

	public function users() {
		$elems = $this->list_all();
		$users = [];
		foreach ($elems as $el) {
			$id = rtrim(ltrim($el, $this->dir), ".yml");
			$users[] = $this->read($id);
		}
		return $users;
	}

	public function dir() {
		return $this->dir;
	}

}

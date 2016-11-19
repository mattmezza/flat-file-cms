<?php

namespace FlatFileCMS\Users;

class User {

	private $username;
	private $password;
	private $encryption;
	private $email;
	private $role;
	private $metas;

	public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

	public function get_meta($name) {
		return $this->metas[$name];
	}

	public function add_meta($name, $value) {
		$this->metas[$name] = $value;
	}

}
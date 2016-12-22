<?php

namespace FlatFileCMS\Plugins;

class BasePlugin {

	protected $conf;

	public function __construct($conf) {
		$this->conf = $conf;
	}

	public function conf($name) {
		return $this->conf[$name];
	}

	public function name() {
		return $this->conf("name");
	}

	public function type() {
		return $this->conf("type");
	}

}

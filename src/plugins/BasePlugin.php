<?php 

namespace FlatFileCMS\Plugins;

class BasePlugin {

	private $c;

	public function __construct($config_file) {
		$general_config = Yaml::parse(file_get_contents($config_file));
		$this->c = array();
		foreach ($general_config as $key => $value) {
			if(strpos("plugin.", $key)==0) {
				$this->c[str_replace("plugin.", "", $key)] = $value;
			}
		}
	}

	public function config($name) {
		return $this->c[$name];
	}

	public function config($name, $value) {
		$this->c[$name] = $value;
	}

}
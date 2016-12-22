<?php

namespace FlatFileCMS\Conf;

use \Symfony\Component\Yaml\Yaml;

use FlatFileCMS\Exceptions\ContentNotFound;

class Conf {

  private $conf;

  public function __construct($conf_file) {
    $this->read($conf_file);
  }

	public function conf($name, $value = "") {
    if($value=="")
		  return $this->conf[$name];
    else
      $this->conf[$name] = $value;
	}

  public function read($conf_file) {
    if(!file_exists($conf_file))
      throw new ContentNotFound($conf_file, $conf_file, ContentNotFound::CONF);
    $this->conf = Yaml::parse(file_get_contents($conf_file));
  }

  public function write($conf_file) {
    return file_put_contents($conf_file, Yaml::dump($this->conf));
  }

}

 ?>

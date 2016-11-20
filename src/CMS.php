<?php

namespace FlatFileCMS;

use \Symfony\Component\Yaml\Yaml;

use \FlatFileCMS\Content\Pages
    \FlatFileCMS\Content\Posts;

class CMS {
  public $pages;
  public $posts;
  public $conf;

  public function __construct($config_file) {
    $this->conf = Yaml::parse(file_get_contents($config_file));
    $this->pages = new Pages($this->conf);
    $this->posts = new Posts($this->conf);
  }
}

 ?>

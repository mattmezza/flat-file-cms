<?php

namespace FlatFileCMS;

use \Symfony\Component\Yaml\Yaml;

use \FlatFileCMS\Content\Pages
    \FlatFileCMS\Content\Posts
    \FlatFileCMS\Users\Users;

class CMS {
  public $pages;
  public $posts;
  public $users;
  public $conf;

  public function __construct($config_file) {
    $this->conf = Yaml::parse(file_get_contents($config_file));
    $this->pages = new Pages($this->conf);
    $this->posts = new Posts($this->conf);
    $this->users = new Users($this->conf);
  }
}

 ?>

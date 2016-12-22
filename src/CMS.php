<?php

namespace FlatFileCMS;

use \Symfony\Component\Yaml\Yaml;

use \FlatFileCMS\Content\Pages,
    \FlatFileCMS\Content\Posts,
    \FlatFileCMS\Users\Users;

class CMS {
  public $pages;
  public $posts;
  public $users;
  public $conf;

  public function __construct($conf) {
    $this->conf = $conf;
    $this->pages = new Pages($this->conf);
    $this->posts = new Posts($this->conf);
    $this->users = new Users($this->conf);
  }

  public function add_pages_plugin($plugin) {
    $this->pages->add_plugin($plugin);
  }

  public function add_posts_plugin($plugin) {
    $this->posts->add_plugin($plugin);
  }

  public function add_users_plugin($plugin) {
    $this->users->add_plugin($plugin);
  }
}

 ?>

<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;

use \FlatFileCMS\Conf\Conf;

class Contents {

	protected $parsedown;
	protected $conf;
	protected $cache;
	protected $cache_enabled = false;
	protected $site_url;
	protected $dir;
	protected $plugins;

	public function __construct($conf) {
		$this->conf = $conf;
		$this->parsedown = new ParsedownExtra();
		$ca = $this->conf->conf("cache");
		if($ca["enabled"]=="yes") {
			$this->cache = new Cache(rtrim($this->conf->conf("cache.dir"), "/"));
			$this->cache_enabled = true;
		}
		$this->site_url = rtrim($this->conf->conf("url"), "/");
		$this->plugins = array();
	}

	public function read_from_cache($slug) {
		return $this->cache->read($slug);
	}

	public function dir() {
    return $this->dir;
  }

	public function add_plugin($plugin) {
		$this->plugins[] = $plugin;
	}

}

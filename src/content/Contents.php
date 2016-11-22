<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;

class Contents {

	protected $parsedown;
	protected $conf;
	protected $cache;
	protected $cache_enabled = false;
	protected $site_url;
	protected $dir;

	public function __construct($conf) {
		$this->conf = $conf;
		$this->parsedown = new ParsedownExtra();
		if($this->conf["cache.enabled"]=="yes") {
			$this->cache = new Cache(rtrim($this->conf["cache.dir"], "/"));
			$this->cache_enabled = true;
		}
		$this->site_url = rtrim($this->conf["url"], "/");
	}

	public function read_from_cache($slug) {
		return $this->cache->read($slug);
	}

	public function dir() {
    return $this->dir;
  }

}

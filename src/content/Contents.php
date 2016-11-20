<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;

class Contents {

	private $parsedown;
	private $conf;
	private $cache;
	private $cache_enabled = false;
	private $site_url;

	public function __construct($conf) {
		$this->conf = $config;
		$this->parsedown = new ParsedownExtra();
		if($this->conf["cache_enabled"]=="yes") {
			$this->cache = new Cache($this->conf["cache_dir"]);
			$this->cache_enabled = true;
		}
		$this->site_url = $this->conf["url"];
	}

	public function read_from_cache($slug) {
		return $this->cache->read($slug);
	}

}

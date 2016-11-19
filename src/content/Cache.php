<?php

namespace FlatFileCMS\Content;
use Symfony\Component\Yaml\Yaml;

class Cache {

	private $cache_dir;

	public function __construct($cache_dir) {
		$this->cache_dir = $cache_dir;
	}

	public function read_page($slug) {
		$yml_str_from_cache = file_get_contents($this->cache_dir."/".$slug.".cached.yml");
		$from_cache = Yaml::parse($yml_str_from_cache);
		$page = new CachedPage();
		$page->html = $from_cache["cached"];
		$page->metas = $from_cache["metas"];
		$page->cached_on = $from_cache["cached_on"];
		return $page;
	}

	public function read_post($slug) {
		$yml_str_from_cache = file_get_contents($this->cache_dir."/".$slug.".cached.yml");
		$from_cache = Yaml::parse($yml_str_from_cache);
		$post = new CachedPost();
		$post->html = $from_cache["cached"];
		$post->metas = $from_cache["metas"];
		$post->cached_on = $from_cache["cached_on"];
		return $post;
	}

	public function cache($content) {
		$to_write = array(
			"metas" => $this->metas,
			"cached"=> $this->html,
			"cached_on"=>time()
		);
		file_put_contents($this->cache_dir."/".$content->get_name().".cached.yml", Yaml::dump($to_write));
	}

	public function invalidate($slug) {
		unlink($this->cache_dir."/".$slug.".cached.yml");
	}
}
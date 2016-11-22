<?php

namespace FlatFileCMS\Content;
use Symfony\Component\Yaml\Yaml;
use FlatFileCMS\Exceptions\ContentNotFound;

class Cache {

	private $cache_dir;

	public function __construct($cache_dir) {
		$this->cache_dir = $cache_dir;
	}

	public function read($slug) {
		$filename = $this->cache_dir."/".$slug.".cached.yml";
		if(!file_exists($filename))
			throw new ContentNotFound($slug, $filename, ContentNotFound::CACHED);
		$yml_str_from_cache = file_get_contents($filename);
		$from_cache = Yaml::parse($yml_str_from_cache);
		$cached_content = null;
		if($from_cache["type"]==CachedContent::POST) {
			$cached_content = new CachedPage();
		} elseif ($from_cache["type"]==CachedContent::PAGE) {
			$cached_content = new CachedPost();
		}
		$cached_content->html = $from_cache["cached"];
		$cached_content->metas = $from_cache["metas"];
		$cached_content->cached_on = $from_cache["cached_on"];
		return $cached_content;
	}

	public function cache($content) {
		$to_write = array(
			"type"=>$content->type(),
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

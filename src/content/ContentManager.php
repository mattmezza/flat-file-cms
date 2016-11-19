<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;
use \Symfony\Component\Yaml\Yaml;

class ContentManager {

	private $parsedown;
	private $conf;
	private $cache;
	private $cache_enabled = false;
	private $pages_dir;
	private $posts_dir;

	public function __construct($conf) {
		$this->conf = $config;
		$this->parsedown = new ParsedownExtra();
		if($this->conf["cache_enabled"]=="yes") {
			$this->cache = new Cache($this->conf["cache_dir"]);
			$this->cache_enabled = true;
		}
		$this->pages_dir = $this->conf["pages_dir"];
		$this->posts_dir = $this->conf["posts_dir"];
	}

	public function write($content) {
		if($content instanceof Page) {
			file_put_contents($this->pages_dir."/".$content->slug.".md", $content->markdown);
			file_put_contents($this->pages_dir."/".$content->slug.".yml", Yaml::dump($content->metas));
		} elseif($content instanceof Post) {
			file_put_contents($this->posts_dir."/".$content->full_slug().".md", $content->markdown);
			file_put_contents($this->posts_dir."/".$content->full_slug().".yml", Yaml::dump($content->metas));
		}
	}

	public function read_page($slug) {
		$page = new Page();
		$page->markdown = file_get_contents($this->pages_dir."/".$slug.".md");
		$page->html = $this->parsedown->text($page->markdown);
		$page->metas = file_get_contents($this->pages_dir."/".$slug.".yml");
		return $page;
	}

	public function read_post($slug) {
		$post = new Post();
		$post->markdown = file_get_contents($this->posts_dir."/".$slug.".md");
		$post->html = $this->parsedown->text($page->markdown);
		$post->metas = file_get_contents($this->posts_dir."/".$slug.".yml");
		return $post;
	}

	public function read_from_cache($slug) {
		if($this->cache_enabled){
			return $this->cache->read($slug);
		}
	}

	public function delete_page($slug) {
		return unlink($this->pages_dir."/".$slug.".md");
	}

	public function delete_post($slug) {
		return unlink($this->posts_dir."/".$slug.".md");
	}

	public function edit_page($page) {
		if($this->delete($page->slug))
			$this->write($page);
	}

	public function edit_post($post) {
		if($this->delete($post->slug))
			$this->write($post);
	}
}
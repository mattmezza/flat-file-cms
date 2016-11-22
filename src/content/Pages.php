<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;
use \Symfony\Component\Yaml\Yaml;
use \FlatFileCMS\Exceptions\ContentNotFound;

class Pages extends Contents {

  public function __construct($conf) {
    parent::__construct($conf);
    $this->dir = $this->conf["pages.dir"];
  }

  public function edit($page) {
		if($this->delete($page->slug))
			$this->write($page);
	}

  public function delete($slug) {
    return (unlink($this->dir."/".$slug.".md") && unlink($this->dir."/".$slug.".yml"));
	}

  public function read($slug) {
    $filename = $this->dir."/".$slug.".md";
    if(!file_exists($filename))
      throw new ContentNotFound($slug, $filename, ContentNotFound::PAGE);
    $page = new Page();
    $page->markdown = file_get_contents($filename);
    $page->html = $this->parsedown->text($page->markdown);
    $page->metas = file_get_contents($this->dir."/".$slug.".yml");
    return $page;
  }

  public function write($page) {
		file_put_contents(rtrim($this->dir,"/")."/".$page->slug.".md", $page->markdown);
		file_put_contents(rtrim($this->dir,"/")."/".$page->slug.".yml", Yaml::dump($page->metas));
	}

  public function list_all($reverse = true) {
    if ($reverse)
      return array_reverse(glob($this->dir . "/" . "*.md"));
    else
      return glob($this->dir . "/" . "*.md");
  }

  public function links() {
    $pages = $this->list_all();
    $links = array();
    foreach($pages as $el){
      $name_ext = ltrim(ltrim($el, $this->dir));
      $link = $this->site_url.rtrim($name_ext,'.md');
      $links[] = $link;
    }
    return $links;
  }

}

 ?>

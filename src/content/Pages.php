<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;
use \Symfony\Component\Yaml\Yaml;

class Pages extends Contents {

  private $pages_dir;

  public function __construct($conf) {
    super::__construct($conf);
    $this->pages_dir = $this->conf["pages_dir"];
  }

  public function edit($page) {
		if($this->delete($page->slug))
			$this->write($page);
	}

  public function delete($slug) {
		return unlink($this->pages_dir."/".$slug.".md");
	}

  public function read($slug) {
    $page = new Page();
    $page->markdown = file_get_contents($this->pages_dir."/".$slug.".md");
    $page->html = $this->parsedown->text($page->markdown);
    $page->metas = file_get_contents($this->pages_dir."/".$slug.".yml");
    return $page;
  }

  public function write($page) {
		file_put_contents($this->pages_dir."/".$page->slug.".md", $page->markdown);
		file_put_contents($this->pages_dir."/".$page->slug.".yml", Yaml::dump($page->metas));
	}

  public function list($reverse = true) {
    if ($reverse)
      return array_reverse(glob($this->pages_dir . DIRECTORY_SEPARATOR . "*.md"));
    else
      return glob($this->pages_dir . DIRECTORY_SEPARATOR . "*.md");
  }

  public function links() {
    $pages = $this->list();
    $links = array();
    foreach($pages as $el){
      $name_ext = str_replace($this->pages_dir,'',$el);
      $name_ext = str_replace(DIRECTORY_SEPARATOR,'',$name_ext);
      $link = $this->site_url . str_replace('.md','',$name_ext);
      $links[] = $link;
    }
    return $links;
  }

}

 ?>

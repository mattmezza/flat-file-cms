<?php

namespace FlatFileCMS\Content;
use \ParsedownExtra;
use \Symfony\Component\Yaml\Yaml;

class Posts extends Contents {

  private $posts_dir;
	private $posts_per_page;

  public function __construct($conf) {
    super::__construct($conf);
    $this->posts_dir = $this->conf["posts_dir"];
    $this->posts_per_page = $this->conf["posts_per_page"];
  }

  public function write($post) {
		file_put_contents($this->posts_dir."/".$post->full_slug().".md", $post->markdown);
		file_put_contents($this->posts_dir."/".$post->full_slug().".yml", Yaml::dump($post->metas));
	}

  public function read($slug) {
    $post = new Post();
    $post->markdown = file_get_contents($this->posts_dir."/".$slug.".md");
    $post->html = $this->parsedown->text($page->markdown);
    $post->metas = file_get_contents($this->posts_dir."/".$slug.".yml");
    return $post;
  }

  public function edit($post) {
		if($this->delete($post->slug))
			$this->write($post);
	}

  public function delete($slug) {
		return unlink($this->posts_dir."/".$slug.".md");
	}

  public function list($reverse = true) {
    if ($reverse)
      return array_reverse(glob($this->posts_dir . DIRECTORY_SEPARATOR . "*.md"));
    else
      return glob($this->posts_dir . DIRECTORY_SEPARATOR . "*.md");
  }

  public function links() {
    $posts = $this->list();
    $links = array();
    foreach($posts as $k=>$v){
      $arr = explode('_', $v);
      $timestr = str_replace($this->posts_dir,'',$arr[0]);
      $timestr = str_replace(DIRECTORY_SEPARATOR,'',$timestr);
      $bits = explode('-', $timestr);
      $date = strtotime($timestr);
      $link = $this->site_url . date('Y/m/d', $date).'/'.str_replace('.md','',$arr[1]);
      $links[] = $link;
    }
    return $links;
  }

  public function posts($page = 1, $perpage = 0){
    if($perpage == 0){
      $perpage = $this->posts_per_page;
    }
    $posts_files = $this->list();
    // Extract a specific page with results
    $posts_files = array_slice($posts, ($page-1) * $perpage, $perpage);
    $posts = array();
    foreach($posts_file as $v){
      $slug = str_replace(".md", "", str_replace($this->posts_dir, "", $v));
      if($this->cache_enabled) {
        $posts[] = $this->read_from_cache($slug);
      } else {
        $posts[] = $this->read($slug);
      }
    }
    return $posts;
  }

  public function find($year, $month, $day, $name){
    foreach($this->list() as $el) {
      $slug = str_replace($this->posts_dir, "", $el);
      if($slug=="$year-$month-$day"."_$name.md") {
        if($this->cache_enabled) {
          return $this->read_from_cache($slug);
        } else {
          return $this->read($slug);
        }
      }
    }
    throw new ContentNotFound($year."-".$month."-".$day."_".$name.".md", ContentNotFound::POST);
  }

  public function year($year){
    $posts = [];
    foreach($this->list() as $el) {
      $slug = str_replace($this->posts_dir, "", $el);
      $bits = explode("-", $slug);
      if($bits[0]==$year) {
        if($this->cache_enabled) {
          $posts[] = $this->read_from_cache($slug);
        } else {
          $posts[] = $this->read($slug);
        }
      }
    }
    return $posts;
  }

  public function month($year, $month){
    $posts = [];
    foreach($this->list() as $el) {
      $slug = str_replace($this->posts_dir, "", $el);
      $bits = explode("-", $slug);
      if($bits[0]==$year && $bits[1]==$month) {
        if($this->cache_enabled) {
          $posts[] = $this->read_from_cache($slug);
        } else {
          $posts[] = $this->read($slug);
        }
      }
    }
    return $posts;
  }

  public function day($year, $month, $day){
    $posts = [];
    foreach($this->list() as $el) {
      $slug = str_replace($this->posts_dir, "", $el);
      $bits = explode("-", $slug);
      if($bits[0]==$year && $bits[1]==$month && $bits[2]==$day) {
        if($this->cache_enabled) {
          $posts[] = $this->read_from_cache($slug);
        } else {
          $posts[] = $this->read($slug);
        }
      }
    }
    return $posts;
  }

  public function name($name) {
    $posts = [];
    foreach($this->list() as $el) {
      $slug = str_replace($this->posts_dir, "", $el);
      $bits = explode("_", $slug);
      if(strpos($name, $bits[1])!==false) {
        if($this->cache_enabled) {
          $posts[] = $this->read_from_cache($slug);
        } else {
          $posts[] = $this->read($slug);
        }
      }
    }
    return $posts;
  }

  public function pagination($page = 1){
    $total = count($this->list());
    return array(
      'prev'=> $page > 1,
      'prevpage'=>$page-1,
      'next'=> $total > $page*$this->posts_per_page,
      'nextpage'=>$page+1
    );
  }

}

 ?>
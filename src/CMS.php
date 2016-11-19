<?php
namespace FlatFileCMS;

use \ParsedownExtra;
use Symfony\Component\Yaml\Yaml;
use \FlatFileCMS\Exceptions\ContentNotFound;
use \FlatFileCMS\Users\UsersManager;
use \FlatFileCMS\Content\ContentManager;

class CMS {

  private $config;
  private $postsDir;
  private $postPerPage;
  private $siteUrl;
  private $pagesDir;
  private $usersDir;
  private $cache_dir;
  private $cache_enabled;

  private $users_mgr;
  private $content_mgr;

  public function __construct($config_file) {
    $this->config = Yaml::parse(file_get_contents($config_file));
    $this->users_mgr = new UsersManager($this->config);
    $this->content_mgr = new ContentManager($this->config);
  }

  public function page($slug) {
    return $this->content_mgr->read_page($slug);
  }

  public function post_links() {
    $posts = $this->post_names();
    $links = array();
    foreach($posts as $k=>$v){
      $link = new \stdClass;
      $link->path = $v;
      $arr = explode('_', $v);
      $link->name = str_replace('.md', '', $arr[1]);
      $timestr = str_replace($this->postsDir,'',$arr[0]);
      $timestr = str_replace(DIRECTORY_SEPARATOR,'',$timestr);
      $bits = explode('-', $timestr);
      $link->year = $bits[0];
      $link->month = $bits[1];
      $link->day = $bits[2];
      $date = strtotime($timestr);
      $link->url = $this->siteUrl . date('Y/m', $date).'/'.str_replace('.md','',$arr[1]);
      $links[] = $link;
    }
    return $links;
  }

  public function post_names() {
    return array_reverse(glob($this->postsDir . DIRECTORY_SEPARATOR . "*.md"));;
  }

  public function page_names() {
    return array_reverse(glob($this->pagesDir . DIRECTORY_SEPARATOR . "*.md"));
  }

  public function posts($page = 1, $perpage = 0){
    if($perpage == 0){
      $perpage = $this->postPerPage;
    }
    $posts = $this->post_names();
    // Extract a specific page with results
    $posts = array_slice($posts, ($page-1) * $perpage, $perpage);
    $tmp = array();
    foreach($posts as $k=>$v){
      $post = new \stdClass;
      $parsedown = new ParsedownExtra();
      // Extract the date
      $arr = explode('_', $v);
      $post->date = strtotime(str_replace($this->postsDir,'',$arr[0]));
      // The post URL
      $post->url = $this->siteUrl . date('Y/m', $post->date).'/'.str_replace('.md','',$arr[1]);
      $postContent = file_get_contents($v);
      $metasAndContent = preg_split('/-{3,}/', $postContent, 2);
      # if we have metadata defined
      if(count($metasAndContent)==2){
        $toCache = $metasAndContent[0]."\n";
        $metadata = $metasAndContent[0];
        $post->metas = Yaml::parse($metadata);
        if(isset($post->metas['title']))
          $post->title = $post->metas['title'];
        if(isset($post->metas['author'])) {
          // if($this->authors[$post->metas['author']])
          //   $post->metas['author'] = $this->authors[$post->metas['author']];
        }
        $postContent = $metasAndContent[1];
        // Get the contents and convert it to HTML
        $content = $parsedown->text($postContent);
    } else {
        // Get the contents and convert it to HTML
        $content = $parsedown->text($postContent);
        // Extract the title and body
        $arr = explode('</h1>', $content);
        $post->title = str_replace('<h1>','',$arr[0]);
        $content = $arr[1];
      }
      $post->body = $content;
      $tmp[] = $post;
    }
    return $tmp;
  }

  // Find post by year, month and name
  public function find_post($year, $month, $name){
    foreach($this->post_names() as $index => $v){
      if( strpos($v, "$year-$month") !== false && strpos($v, $name.'.md') !== false){
        // Use the posts method to return
        // a properly parsed object
        $arr = $this->posts($index+1,1);
        return $arr[0];
      }
    }
    throw new ContentNotFound($year."-".$month."_".$name.".md", ContentNotFound::POST);
  }

  // Helper function to determine whether
  // to show the pagination buttons
  public function pagination($page = 1){
    $total = count($this->post_names());
    return array(
      'prev'=> $page > 1,
      'prevpage'=>$page-1,
      'next'=> $total > $page*$this->postPerPage,
      'nextpage'=>$page+1
    );
  }

  // Turn an array of posts into a JSON
  public function posts_json($page = 1, $perpage = 0){
    return json_encode($this->posts($page, $perpage));
  }

}

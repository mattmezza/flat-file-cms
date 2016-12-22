<?php

use PHPUnit\Framework\TestCase;
use FlatFileCMS\CMS,
    FlatFileCMS\Conf\Conf,
    FlatFileCMS\Exceptions\ContentNotFound;
use Symfony\Component\Yaml\Yaml;

class GeneralTest extends TestCase {

  private $cms;

  public function __construct() {
    parent::__construct();
    $conf = new Conf(dirname(__FILE__)."/config.yml");
    $this->cms = new CMS($conf);
  }

  public function test_get_page() {
    $page = $this->cms->pages->read("test");
    $this->assertEquals($page->html, "<p>Test</p>");
    try {
      $page = $this->cms->pages->read("testdddd");
    } catch(ContentNotFound $e) {
      $this->assertNotEmpty($e->getMessage());
    }
  }

  public function test_get_post_names() {
    $posts_names = $this->cms->posts->list_all();
    $this->assertGreaterThan(0, count($posts_names));
  }

  public function test_get_page_names() {
    $pages_names = $this->cms->pages->list_all();
    $this->assertGreaterThan(0, count($pages_names));
  }

  /**
   * @depends test_get_post_names
   */
  public function test_find_post() {
    $post = $this->cms->posts->find(2016, 11, 15, "test");
    $this->assertEquals($post->html, "<p>Test</p>");
    try {
      $post = $this->cms->posts->find(2015, 11, 15, "tests");
    } catch(ContentNotFound $e) {
      $this->assertNotEmpty($e->getMessage());
    }
  }

  /**
   * @depends test_get_post_names
   */
  public function test_get_posts() {
    $posts = $this->cms->posts->posts();
    $this->assertEquals($posts[0]->html, "<p>Test</p>");
  }

  /**
   * @depends test_get_post_names
   */
  public function test_pagination() {
    $pagination = $this->cms->posts->pagination();
    $this->assertFalse($pagination["prev"]);
    $this->assertFalse($pagination["next"]);
  }

  /**
   * @depends test_get_post_names
   */
  public function test_get_post_links() {
    $posts_links = $this->cms->posts->links();
    $this->assertEquals($posts_links[0], "http://localhost:8000/2016/11/15/test");
  }

}

 ?>

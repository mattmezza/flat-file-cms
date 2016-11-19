<?php

use PHPUnit\Framework\TestCase;
use FlatFileCMS\BlogManager;
use FlatFileCMS\Exceptions\ContentNotFound;
use Symfony\Component\Yaml\Yaml;

class GeneralTest extends TestCase {

  private $cms;
  

  public function __construct() {
    parent::__construct();
    $this->cms = new CMS(__DIR__."/config.yml");
  }

  public function test_get_page() {
    $page = $this->cms->page("test");
    $this->assertEquals($page->body, "<p>Test</p>");
    try {
      $page = $this->cms->page("testdddd");
    } catch(ContentNotFound $e) {
      $this->assertNotEmpty($e->getMessage());
    }
  }

  public function test_get_post_names() {
    $posts_names = $this->cms->post_names();
    $this->assertEquals(count($posts_names), 1);
  }

  public function test_get_page_names() {
    $pages_names = $this->cms->page_names();
    $this->assertEquals(count($pages_names), 1);
  }

  /**
   * @depends test_get_post_names
   */
  public function test_find_post() {
    $post = $this->cms->find_post(2016, 11, "test");
    $this->assertEquals($post->body, "<p>Test</p>");
    try {
      $post = $this->cms->find_post(2015, 11, "tests");
    } catch(ContentNotFound $e) {
      $this->assertNotEmpty($e->getMessage());
    }
  }

  /**
   * @depends test_get_post_names
   */
  public function test_get_posts() {
    $posts = $this->cms->posts();
    $this->assertEquals($posts[0]->body, "<p>Test</p>");
  }

  /**
   * @depends test_get_posts
   */
  public function test_json_api() {
    $postsjson = json_decode($this->cms->posts_json());
    $this->assertEquals($postsjson[0]->body, "<p>Test</p>");
  }

  /**
   * @depends test_get_post_names
   */
  public function test_pagination() {
    $pagination = $this->cms->pagination();
    $this->assertFalse($pagination["prev"]);
    $this->assertFalse($pagination["next"]);
  }

  /**
   * @depends test_get_post_names
   */
  public function test_get_post_links() {
    $posts_links = $this->cms->post_links();
    $this->assertEquals($posts_links[0]->url, "http://localhost:8000/2016/11/test");
  }

}

 ?>

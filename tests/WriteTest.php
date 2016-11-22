<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use FlatFileCMS\CMS,
    FlatFileCMS\Content\Page,
    FlatFileCMS\Content\Post,
    FlatFileCMS\Exceptions\ContentNotFound;

class WriteTest extends TestCase {

  private $cms;

  public function __construct() {
    parent::__construct();
    $this->cms = new CMS(__DIR__."/config.yml");
  }

  public function test_write_page() {
    $page = new Page();
    $page->slug = "test1";
    $page->metas = array(
      "title"=>"test1"
    );
    $page->markdown = "# test1";
    $this->cms->pages->write($page);
    $this->assertTrue(file_exists(ltrim($this->cms->pages->pages_dir(), "/")."/".$page->slug.".md"));
    $this->assertTrue(file_exists(ltrim($this->cms->pages->pages_dir(), "/")."/".$page->slug.".yml"));
  }

  /**
  *
  * @depends test_write_page
  */
  public function test_delete_page() {
    $this->cms->pages->delete("test1");
    $this->assertFalse(file_exists(ltrim($this->cms->pages->pages_dir(), "/")."/test1.md"));
    $this->assertFalse(file_exists(ltrim($this->cms->pages->pages_dir(), "/")."/test1.yml"));
  }

}

 ?>

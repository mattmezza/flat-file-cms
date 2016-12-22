<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use FlatFileCMS\CMS,
    FlatFileCMS\Conf\Conf,
    FlatFileCMS\Content\Page,
    FlatFileCMS\Content\Post,
    FlatFileCMS\Exceptions\ContentNotFound;

class WriteTest extends TestCase {

  private $cms;

  public function __construct() {
    parent::__construct();
    $conf = new Conf(dirname(__FILE__)."/config.yml");
    $this->cms = new CMS($conf);
  }

  public function test_write_page() {
    $page = new Page();
    $page->slug = "test1";
    $page->metas = array(
      "title"=>"test1"
    );
    $page->markdown = "# test1";
    $this->cms->pages->write($page);
    $this->assertTrue(file_exists($this->cms->pages->dir()."/".$page->slug.".md"));
    $this->assertTrue(file_exists($this->cms->pages->dir()."/".$page->slug.".yml"));
  }

  /**
  *
  * @depends test_write_page
  */
  public function test_delete_page() {
    $this->cms->pages->delete("test1");
    $this->assertFalse(file_exists($this->cms->pages->dir()."/test1.md"));
    $this->assertFalse(file_exists($this->cms->pages->dir()."/test1.yml"));
  }

}

 ?>

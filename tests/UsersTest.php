<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use FlatFileCMS\CMS,
    FlatFileCMS\Conf\Conf,
    FlatFileCMS\Users\User,
    FlatFileCMS\Exceptions\ContentNotFound;

class UsersTest extends TestCase {

  private $cms;

  public function __construct() {
    parent::__construct();
    $conf = new Conf(dirname(__FILE__)."/config.yml");
    $this->cms = new CMS($conf);
  }

  public function test_create_user() {
    $user = new User();
    $user->username = "antonio";
    $user->password = "ciccio";
    $user->encryption = "plain";
    $user->email = "antonio@italia.no";
    $user->role = "writer";
    $user->metas = array(
      "title"=>"test1"
    );
    $this->cms->users->write($user);
    $this->assertTrue(file_exists($this->cms->users->dir()."/".$user->username.".yml"));
  }

  /**
  *
  * @depends test_create_user
  */
  public function test_delete_user() {
    try {
      $this->assertTrue($this->cms->users->delete("antonio"));
      $this->assertFalse(file_exists($this->cms->users->dir()."/".$user->username.".yml"));
    } catch(\Exception $e) {
      echo $e->getMessage()."\n";
    }
  }

  public function test_read_user() {
    try {
      $mattmezza = $this->cms->users->read("mattmezza");
      $this->assertEquals($mattmezza->username, "mattmezza");
      $user = $this->cms->users->read("notexistant");
      $this->assertEquals($user->username, "rajflkalkf");
    } catch(ContentNotFound $e) {
      $this->assertNotEquals("", $e->getMessage());
    }
  }

  public function test_users() {
    $users = $this->cms->users->users();
    $this->assertGreaterThan(0, count($users));
  }

}

 ?>

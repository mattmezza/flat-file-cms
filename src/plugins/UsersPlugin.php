<?php

namespace FlatFileCMS\Plugins;

interface UsersPlugin extends Plugin {

  public function onUserAdded($user, $file, $creator = null);

  public function onUserDeleted($user, $file, $destroyer = null);

  public function onUserEdited($user, $file, $editor = null);

}

 ?>

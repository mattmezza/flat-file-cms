<?php

namespace FlatFileCMS\Plugins;

interface ContentPlugin extends Plugin {

	public function onContentCreated($name, $file, $user);

	public function onContentDeleted($name, $file, $user);

	public function onContentEdited($name, $file, $user);

}
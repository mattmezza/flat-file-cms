<?php

namespace FlatFileCMS\Plugins;

interface ContentPlugin extends Plugin {

	public function onContentCreated($name, $file, $user = null);

	public function onContentDeleted($name, $file, $user = null);

	public function onContentEdited($name, $file, $user = null);

}

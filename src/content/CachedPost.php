<?php

namespace FlatFileCMS\Content;

class CachedPost extends Post implements CachedContent {

	protected $cached_on;

	public function type() {
		return CachedContent::POST;
	}

}

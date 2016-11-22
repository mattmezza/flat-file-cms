<?php

namespace FlatFileCMS\Content;

class CachedPage extends Page implements CachedContent {

	protected $cached_on;

	public function type() {
		return CachedContent::PAGE;
	}

}

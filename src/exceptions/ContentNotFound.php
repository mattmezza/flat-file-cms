<?php

namespace FlatFileCMS\Exceptions;

class ContentNotFound extends \Exception {
	private $slug;
	private $type;
	private $filename;


	public function __construct($slug, $filename, $type) {
		$this->slug = $slug;
		$this->type = $type;
		$this->filename = $filename;
		$this->message = "The $this->type named: $this->slug could not be found.";
	}

	const PAGE = "page";
	const POST = "post";
	const USER = "user";
	const METAS = "metas (.yml) file";
	const CACHED = "cached file";
}

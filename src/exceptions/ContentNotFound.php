<?php

namespace FlatFileCMS\Exceptions;

class ContentNotFound extends \Exception {
	private $name;
	private $type;

	public function __construct($name, $type) {
		$this->name = $name;
		$this->type = $type;
		$this->message = "The $this->type named: $this->name could not be found.";
	}

	const PAGE = "page";
	const POST = "post";
}
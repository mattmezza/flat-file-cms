<?php

namespace FlatFileCMS\Content;


abstract class Content {

	protected $slug;
	protected $metas;
	protected $html;

	public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

	public function add_meta($key, $value) {
		$this->metas[$key] = $value;
	}

	public function get_meta($key) {
		return $this->metas[$key];
	}


}
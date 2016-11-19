<?php

namespace FlatFileCMS\Content;

class Post extends Content {

	protected $markdown;
	protected $category;
	protected $tags;
	protected $year;
	protected $month;
	protected $day;

	public function add_tag($tag) {
		if(!$this->has_tag($tag))
			$this->tags[] = $tag;
	}

	public function has_tag($tag) {
		return in_array($tag, $this->tags);
	}

	public function full_slug() {
		return $this->year."-".$this->month."-".$this->day."_".$this->slug;
	}

}
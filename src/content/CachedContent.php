<?php

namespace FlatFileCMS\Content;

interface CachedContent {

  const POST = "post";
  const PAGE = "page";

  public function type();
  
}

 ?>

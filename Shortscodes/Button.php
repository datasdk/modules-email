<?php 

namespace Modules\Email\Shortscodes;


class Button {

  public function register($shortcode, $content, $compiler, $name, $viewData)
  {

    $url = $shortcode->url;

    return "<a href='".$url."' class='button button-primary'>".$content."</a>";


  }
  
}
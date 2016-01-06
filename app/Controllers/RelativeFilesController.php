<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use TimberPost;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;
use AgreablePugpigPlugin\Controllers\ResponseController;

class RelativeFilesController {

  function __construct() {
    $this->respond = new ResponseController;
  }

  public function index($year, $month, $day, $slug) {
    $post_object = get_page_by_path($slug ,OBJECT,'post');
    $post = new TimberPost($post_object->ID);
    $post_link = get_permalink($post_object->ID);
    $file_html = file_get_contents($post_link);
    $base_url = WP_HOME;
    $new_string = str_replace($base_url, "../../../..", $file_html);
    return $this->respond->success($new_string, $post->post_modified_gmt);
  }


}

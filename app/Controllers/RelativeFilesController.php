<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use TimberPost;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class RelativeFilesController {

  public function index($year, $month, $day, $slug) {
    $post_object = get_page_by_path($slug ,OBJECT,'post');
    $post = get_permalink($post_object->ID);
    $file_html = file_get_contents($post);
    $base_url = WP_HOME;
    $new_string = str_replace($base_url, "../../../..", $file_html);
    return $new_string;
  }

  public function send($string, Http $http) {
    $headers = array();
    response($string, 200, $headers);
  }

}

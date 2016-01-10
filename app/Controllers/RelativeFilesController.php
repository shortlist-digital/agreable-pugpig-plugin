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

  public function bundle($slug) {
    $post_object = get_page_by_path($slug ,OBJECT,'pugpig_ad_bundle');
    $post = new TimberPost($post_object->ID);
    $path = get_post_meta($post->id, 'ad_bundle_directory')[0];
    $index_file = $path.$post->ad_bundle_html_file;
    $file_html = file_get_contents($index_file);
    return $this->respond->success($file_html, $post->post_modified_gmt);
  }


}

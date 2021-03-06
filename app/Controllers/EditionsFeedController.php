<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use TimberPost;
use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;
use AgreablePugpigPlugin\Controllers\ResponseController;

use AgreablePugpigPlugin\Services\EditionPackageReader;

class EditionsFeedController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
    $this->feedGenerator = new FeedGeneratorController;
    $this->respond = new ResponseController;


  }

  function package_list($id, Http $http) {
    $edition = new TimberPost($id);
    $epr = new EditionPackageReader($id);
    return $this->respond->package($epr->package_string(), $edition->post_modified_gmt);
  }

  function get_feed_data($id) {
    $object = new \StdClass;
    $edition = new TimberPost($id);
    $object->site_name = get_bloginfo('name');
    $object->edition_key = $edition->edition_key;
    $object->edition_title = $edition->post_title;
    $object->atom_url = $this->linkGenerator->edition_atom_url($id);
    if (is_array($edition->flatplan)) {
      $object->post_array = $edition->flatplan;
    }
    $modified_timestamp = strtotime($edition->post_modified);
    $last_updated = $this->feedGenerator->convert_timestamp($modified_timestamp);
    $object->last_updated = $last_updated;
    return $object;
  }

  public function get_post_data($post_array) {
    $post_object_array = array();
    $object = new \StdClass;
    foreach ($post_array as $post_id) {
      array_push($post_object_array, $this->build_post_object($post_id));
    }
    return $post_object_array;
  }

  public function build_post_object($post_id) {
    $post_data = get_post($post_id);
    $object = new \StdClass;
    $object->id = $post_id;
    $object->section = get_the_category($post_id)[0]->name;
    $permalink = get_permalink($post_id);
    $object->permalink = $permalink;
    $object->manifest = $this->feedGenerator->convert_permalink_to_manifest($permalink);
    $object->index = $this->feedGenerator->convert_permalink_to_index($permalink);
    $object->post_title = $post_data->post_title;
    $modified_timestamp = strtotime($post_data->post_modified);
    $last_updated = $this->feedGenerator->convert_timestamp($modified_timestamp);
    $object->last_updated = $last_updated;
    $published_time = $this->feedGenerator->convert_timestamp(strtotime(get_the_time('c', $post_id)));
    $object->published_time = $published_time;
    return $object;
  }

  public function feed($id) {
    $feed_data = $this->get_feed_data($id);
    if (isset($feed_data->post_array)) {
      $post_data = $this->get_post_data($feed_data->post_array);
    } else {
      $post_data = array();
    }
    return $this->respond->atom($this->feedGenerator->edition_feed($feed_data, $post_data), $feed_data->last_updated);
  }
}

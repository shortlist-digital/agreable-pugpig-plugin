<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class EditionsFeedController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
    $this->feedGenerator = new FeedGeneratorController;
  }

  function get_feed_data($id) {
    $object = new \StdClass;
    $data = get_post($id);
    $custom = get_post_custom($id);
    $object->site_name = get_bloginfo('name');
    $object->edition_key = $custom['edition_key'][0];
    $object->edition_title = $data->post_title;
    $object->atom_url = $this->linkGenerator->edition_atom_url($id);
    $object->post_array = unserialize($custom['pugpig_edition_contents_array'][0]);
    $modified_timestamp = strtotime($data->post_modified);
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

  public function feed($id, Http $http) {
    $feed_data = $this->get_feed_data($id);
    $post_data = $this->get_post_data($feed_data->post_array);
    $headers = [];
    $headers['Content-Type'] = "application/atom+xml";
    return response($this->feedGenerator->edition_feed($feed_data, $post_data), 200, $headers);
  }
}
<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use TimberPost;
use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class EditionsFeedController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
    $this->feedGenerator = new FeedGeneratorController;
  }

  function package_list($id, Http $http) {
    $edition = new TimberPost($id);
    $upload_dir = wp_upload_dir();
    $packages_dir = $upload_dir['basedir']."/pugpig-api/packages";
    $files = glob($packages_dir."/*.xml");
    foreach($files as $file) {
      $name = pathinfo($file, PATHINFO_FILENAME);
      if (strpos(strtolower($name), strtolower($edition->edition_key))) {
        $headers['Content-Disposition'] = "inline";
        $headers['Content-Type'] = "application/pugpigpkg+xml";
        $headers['X-Pugpig-Status'] = "published";
        return response(file_get_contents($file), 200, $headers);
      }
    }
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

  public function feed($id, Http $http) {
    $feed_data = $this->get_feed_data($id);
    if (isset($feed_data->post_array)) {
      $post_data = $this->get_post_data($feed_data->post_array);
    } else {
      $post_data = array();
    }
    $headers = [];
    $headers['Content-Type'] = "application/atom+xml";
    return response($this->feedGenerator->edition_feed($feed_data, $post_data), 200, $headers);
  }
}

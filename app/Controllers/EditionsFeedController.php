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
    $object->edition_key = $custom['edition_key'][0];
    $object->edition_title = $data->post_title;
    $object->atom_url = $this->linkGenerator->edition_atom_url($id);
    $modified_timestamp = strtotime($data->post_modified);
    $last_updated = $this->feedGenerator->convert_timestamp($modified_timestamp);
    $object->last_updated = $last_updated;
    return $object;
  }

  public function feed($id, Http $http) {
    $feed_data = $this->get_feed_data($id);
    $headers = [];
    $headers['Content-Type'] = "application/atom+xml";
    return response($this->feedGenerator->edition_feed($feed_data), 200, $headers);
  }
}

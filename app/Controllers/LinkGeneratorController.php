<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Helper;

class LinkGeneratorController {

  function __construct() {
    global $wp_rewrite;
    $this->manifest_file_name = "/pugpig_atom_contents.manifest";
    add_action('init', array($this, 'add_endpoints'), 10);
  }

  function add_endpoints () {
    // We add this to the end of the HTML files so we can save them
    // We use query string name if we have draft posts or no peramalinks
    add_rewrite_endpoint('pugpig_index.html', EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES, false); // Adds pugpig.html as default document to permalinks
    add_rewrite_endpoint('pugpig.manifest', EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds manifest to permalinks
    add_rewrite_endpoint('pugpig_package_contents.manifest', EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds package files
    add_rewrite_endpoint('pugpig_index.html', EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds ATOM XML files
  }

  public function edition_atom_url($edition_id) {
    return get_bloginfo('url') . "/editionfeed/". $edition_id.$this->manifest_file_name;;
  }

  public function edition_preview_url($edition_id) {
    $base = Helper::pluginDirectory() . "reader/reader.html?atom=";
    $edition_atom_feed = urlencode($this->edition_atom_url($edition_id));
    return $base.$edition_atom_feed;
  }

  public function edition_manifest_url($edition_id) {
    return get_bloginfo('url') . "/editionfeed/". $edition_id."pugpig_package_list.manifest";
  }

}


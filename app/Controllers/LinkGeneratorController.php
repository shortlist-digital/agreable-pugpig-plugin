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
    define( 'PUGPIG_HTML_FILE_NAME', 'pugpig_index.html');
    define( 'PUGPIG_HTML_MANIFEST_NAME', 'pugpig.manifest'); // WP won't allow dots: index.manifest (Boo!)
    define( 'PUGPIG_EDITION_PACKAGE_FILE_NAME', 'pugpig_package_list.manifest');
    define( 'PUGPIG_ATOM_FILE_NAME', 'pugpig_atom_contents.manifest');
    add_rewrite_endpoint('pugpig_index.html', EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES, false); // Adds pugpig.html as default document to permalinks
    add_rewrite_endpoint(PUGPIG_HTML_MANIFEST_NAME, EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds manifest to permalinks
    add_rewrite_endpoint(PUGPIG_EDITION_PACKAGE_FILE_NAME, EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds package files
    add_rewrite_endpoint(PUGPIG_ATOM_FILE_NAME, EP_PERMALINK | EP_ROOT | EP_SEARCH | EP_PAGES); // Adds ATOM XML files
  }

  public function edition_atom_url($edition_id) {
    return get_bloginfo('url') . "/editionfeed/". $edition_id.$this->manifest_file_name;;
  }

  public function edition_preview_url($edition_id) {
    $base = Helper::pluginDirectory() . "reader/reader.html?atom=";
    $edition_atom_feed = urlencode($this->edition_atom_url($edition_id));
    return $base.$edition_atom_feed;
  }

}


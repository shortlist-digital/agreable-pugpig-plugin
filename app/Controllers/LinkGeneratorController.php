<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Helper;

class LinkGeneratorController {

  function __construct() {
    $this->manifest_file_name = "/pugpig_atom_contents.manifest";
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


<?php namespace AgreablePugpigPlugin\Services;

use TimberPost;
use Rych\ByteSize\ByteSize;

class EditionPackageReader {

  function __construct($post_id) {
    $this->edition = new TimberPost($post_id);
  }

  public function package_string() {
    $edition = $this->edition;
    $upload_dir = wp_upload_dir();
    $packages_dir = $upload_dir['basedir']."/pugpig-api/packages";
    $files = glob($packages_dir."/*.xml");
    foreach($files as $file) {
      $name = pathinfo($file, PATHINFO_FILENAME);
      if (strpos(strtolower($name), strtolower($edition->edition_key))) {
        return file_get_contents($file);
      }
    }
    return false;
  }

  public function package_size() {
    $bytesize = new ByteSize;
    $simple = $this->package_string();
    $p = xml_parser_create();
    xml_parse_into_struct($p, $simple, $vals, $index);
    xml_parser_free($p);
    return $bytesize->format($vals[0]['attributes']['SIZE']);
  }

}

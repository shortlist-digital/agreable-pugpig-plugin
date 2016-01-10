<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Controllers\PostManifestController;

use TimberPost;

class BundleManifestController {
  function __construct() {
    $this->manifest_builder = new PostManifestController;
  }

  public function index($slug) {
    $post_object = get_page_by_path($slug ,OBJECT,'pugpig_ad_bundle');
    $this->post = new \TimberPost($post_object);
    $this->manifest_builder->set_post($this->post);
    $directory = get_post_meta($this->post->id, 'ad_bundle_directory')[0];
    $this->files = $this->get_files_from_directory($directory);
    $last_modified = $this->post->post_modified_gmt;
    $manifest = $this->build_manifest();
    return $this->manifest_builder->respond->manifest($manifest, $last_modified);
  }

  function build_manifest() {
    ob_start();
    $this->manifest_builder->cache_header();
    foreach($this->files as $file):
      $file = $this->manifest_builder->root_to_relative_url($file);
      $this->manifest_builder->line($file);
    endforeach;
    return ob_get_clean();
  }

  public function get_files_from_directory($dir, $results = array()) {
    $files = scandir($dir);

    foreach($files as $key => $value){
      $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
      if(!is_dir($path)) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (in_array($ext, $this->manifest_builder->extension_whitelist())) {
          $results[] = $path;
        }
      } else if($value != "." && $value != ".." && $value != "__MACOSX") {
        $results = self::get_files_from_directory($path, $results);
        //$results = array_merge($new, $results);
      }
    }

    return $results;
  }

}


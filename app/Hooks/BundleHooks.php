<?php namespace AgreablePugpigPlugin\Hooks;

use add_action;
use add_filter;
use StdClass;
use ZipArchive;
use Jigsaw;
use Timber;
use TimberPost;

class BundleHooks {
  public function init() {
    add_action('save_post', array($this, 'run_hooks'), 13, 1);
    add_filter('template_include', array($this, 'render_bundle'), 1, 1);
  }

  function render_bundle($template) {
    global $post;

    if (is_singular('pugpig_ad_bundle')) {
      $post = new TimberPost($post->ID);
      $html_file = $post->ad_bundle_html_file;
      $attachment_id = $post->ad_bundle_zip_file;
      $context = Timber::get_context();
      $context['html_file'] = $this->get_unzip_url($attachment_id, $html_file);
      echo view('@AgreablePugpigPlugin/bundle.twig', $context)->getBody();
    } else {
      return $template;
    }
  }

  function get_unzip_url($post_id, $html_file) {
    return PUGPIG_BUNDLE_URL."/$post_id/$html_file";
  }

  function run_hooks($post_id) {
    $post = new TimberPost($post_id);
    if ($this->check($post)) { return; }
    if (empty($post->ad_bundle_zip_file)) { return ; }
    return $this->process_bundle($post);
  }

  function check(TimberPost $post) {
    if (wp_is_post_revision($post->ID)) {return true;}
    if ($post->post_type !== 'pugpig_ad_bundle') {return true;}
    return false;
  }

  function process_bundle(TimberPost $post) {
    $id = $post->ad_bundle_zip_file;
    $zip_file = new StdClass();
    $zip_file->path = get_attached_file($id);
    $zip_file->url = wp_get_attachment_url($id);
    $zip_file->html_file = $post->ad_bundle_html_file;
    $directory = $this->unzip($post, $zip_file);
    add_post_meta($post->id, 'ad_bundle_directory', $directory);
    if (empty($post->ad_bundle_html_file)) {
      $file_name = $this->get_html_file($directory, array('htm', 'html'));
      update_field('ad_bundle_html_file', $file_name, $post->id);
    }
  }

  function get_html_file($dir, $ext, $rel = '') {
    if (!is_array($ext)) $ext = array($ext);
    $contents = scandir($dir);
    if (!$contents) return '';
    $files = array();
    $dirs = array();

    foreach ($contents as $item)
      if ($item != '.' && $item != '..')
        if (is_dir($dir . '/' . $item))
          $dirs[] = $item;
        else
          $files[] = $item;

    foreach ($files as $file)
      if (in_array(pathinfo(strtolower($file), PATHINFO_EXTENSION), $ext) && substr($file, 0, 1) != '.')
        return $rel . '/' . $file;

    foreach ($dirs as $child) {
      $subdir = $dir . '/' . $child;
      $check = $this->get_html_file($subdir, $ext, $child);
      if ($check !== '') {
        return $rel . ($rel == '' ? '' : '/') . $check;
      }
    }
    return '';

  }

  function unzip($post, $zip_file) {
    $unzip_to_dir = PUGPIG_BUNDLE_DIR."/$post->ad_bundle_zip_file";
    $this->ensure_directory_exists($unzip_to_dir);
    $zip = new ZipArchive();
    if ($zip->open($zip_file->path) === TRUE) {
      $zip->extractTo($unzip_to_dir);
      $zip->close();
      Jigsaw::show_notice("Unzipped file for $post->post_title", 'updated');
      return $unzip_to_dir;
    } else {
      Jigsaw::show_notice("Error unzipping the ZIP file for $post->post_title", 'error');
    }

  }

  function ensure_directory_exists($path) {
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
  }

}

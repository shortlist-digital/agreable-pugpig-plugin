<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\PugpigBridgeController;

class EditionsAdminController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
    $this->pugpigBridge = new PugpigBridgeController;
  }

  public function init() {
    $this->remove_columns();
    $this->add_columns();
  }

  function remove_columns() {
    \Jigsaw::remove_column('pugpig_edition', 'date');
  }

  function add_columns() {
    $this->add_cover();
    $this->add_actions();
    $this->add_edition_date();
    $this->add_status();
    $this->add_tags();
  }

  function add_cover() {
    \Jigsaw::add_column('pugpig_edition', 'Cover Image', function($pid) {
      $width = 90;
      $height = 120;
      set_post_thumbnail_size($width, $height);
      $cover_html = get_the_post_thumbnail($pid);
      if (empty($cover_html)) {
        $thumbnail = Helper::assetUrl('/img/no-cover.jpg');
        $cover_html = view('@AgreablePugpigPlugin/cover-column.twig', array(
          'width' => $width,
          'height' => $height,
          'thumbnail' => $thumbnail
        ))->getBody();
      }
      echo $cover_html;
    });
  }

  function add_actions() {
    \Jigsaw::add_column('pugpig_edition', 'Actions', function($pid) {
      $web_preview = $this->linkGenerator->edition_preview_url($pid);
      echo view('@AgreablePugpigPlugin/actions-column.twig', array(
        'web_preview_url' => $web_preview,
        'packager_url' => $this->pugpigBridge->packager_url($pid)
      ))->getBody();
    });
  }

  function add_edition_date() {
    \Jigsaw::add_column('pugpig_edition', 'Edition Date', function($pid) {
      $custom = get_post_custom($pid);
      $edition_date = date("Y-m-d");
      if (isset($custom["edition_date"])) {
        $edition_date = $custom["edition_date"][0];
      }
      echo $edition_date;
    });
  }

  function add_status() {
    \Jigsaw::add_column('pugpig_edition', 'Status', function($pid) {

      $post_status = get_post_status($pid);
      if ($post_status === 'publish') {
        $post_status = '<em>published</em>';
      } else {
        $post_status = "<strong>$post_status</strong>";
      }

      $custom = get_post_custom($pid);
      $page_count = false;
      if (isset($custom['pugpig_edition_contents_array'])) {
        $page_count = get_post_custom($pid)['pugpig_edition_contents_array'][0];
      }

      $timeAgo = new \TimeAgo();
      $post_time = get_post_modified_time('G', true, $pid);
      $post_time = date("Y\/n\/j\ H:i:s", $post_time);
      $last_update = $timeAgo->inWords($post_time);

      echo view('@AgreablePugpigPlugin/status-column.twig', array(
        'post_status' => $post_status,
        'custom' => $page_count ? count(unserialize($page_count)) : 0,
        'last_updated' => $last_update
      ))->getBody();
    });
  }

  function add_tags() {
      \Jigsaw::add_column('pugpig_edition', 'Tags', function($pid) {
        echo wp_get_post_tags($pid);
      });
  }
}

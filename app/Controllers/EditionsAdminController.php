<?php namespace AgreablePugpigPlugin\Controllers;

use Timber;
use TimberPost;
use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\PugpigBridgeController;

use AgreablePugpigPlugin\Services\EditionPackageReader;

class EditionsAdminController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
    $this->pugpigBridge = new PugpigBridgeController;
  }

  public function init() {
    $this->remove_columns();
    $this->add_columns();
    \add_action('do_meta_boxes', array($this, 'change_image_box'), 12, 3);
    \add_filter( 'admin_post_thumbnail_html', array($this, 'custom_admin_post_thumbnail_html'), 12, 3);
    \add_filter('acf/fields/relationship/query', array($this, 'filter_flatplan_taxonomy'), 12, 3);
  }

  function change_image_box() {
    remove_meta_box( 'postimagediv', 'pugpig_edition', 'side' );
    add_meta_box('postimagediv', __('Edition Cover'), 'post_thumbnail_meta_box', 'pugpig_edition', 'side', 'default');
  }

  function custom_admin_post_thumbnail_html( $content ) {
    $content = str_replace( __( 'Remove featured image' ), __( 'Remove Edition Cover' ), $content);
    return $content = str_replace( __( 'Set featured image' ), __( 'Set Edition Cover' ), $content);
  }

  function filter_flatplan_taxonomy($args, $field, $post_id) {
    $args['order'] = 'DESC';
    $args['orderby'] = 'modified';
    $args['tag_id'] = (new \TimberPost($post_id))->edition_number[0];
    return $args;
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

      $edition = new \TimberPost($pid);

      $post_status = get_post_status($pid);
      if ($post_status === 'publish') {
        $post_status = '<em>published</em>';
      } else {
        $post_status = "<strong>$post_status</strong>";
      }

      $custom = get_post_custom($pid);
      $page_count = false;

      if ($edition->flatplan) {
        $page_count = count($edition->flatplan);
      }

      $timeAgo = new \TimeAgo();
      $post_time = get_post_modified_time('G', true, $pid);
      $post_time = date("Y\/n\/j\ H:i:s", $post_time);
      $last_update = $timeAgo->inWords($post_time);

      $epr = new EditionPackageReader($pid);


      echo view('@AgreablePugpigPlugin/status-column.twig', array(
        'post_status' => $post_status,
        'custom' => $page_count ? $page_count : 0,
        'last_updated' => $last_update,
        'package_size' => $epr->package_size()
      ))->getBody();
    });
  }

  function add_tags() {
      \Jigsaw::add_column('pugpig_edition', 'Tags', function($pid) {
        echo wp_get_post_tags($pid);
      });
  }
}

<?php namespace AgreablePugpigPlugin\Controllers;

use Timber;
use TimberPost;
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
    \add_action('do_meta_boxes', array($this, 'change_image_box'), 12, 3);
    \add_filter( 'admin_post_thumbnail_html', array($this, 'custom_admin_post_thumbnail_html'), 12, 3);
    \add_filter('acf/fields/relationship/query', array($this, 'filter_flatplan_taxonomy'), 12, 3);
    \add_filter('wp_insert_post_data', array($this, 'check_for_tag_update'), 12, 3);
  }

  function change_image_box() {
    remove_meta_box( 'postimagediv', 'pugpig_edition', 'side' );
    add_meta_box('postimagediv', __('Edition Cover'), 'post_thumbnail_meta_box', 'pugpig_edition', 'side', 'default');
  }

  function custom_admin_post_thumbnail_html( $content ) {
    $content = str_replace( __( 'Remove featured image' ), __( 'Remove Edition Cover' ), $content);
    return $content = str_replace( __( 'Set featured image' ), __( 'Set Edition Cover' ), $content);
  }

  function check_for_tag_update($post_data, $post_array) {
    $post_id = $post_array['ID'];
    $post = new TimberPost($post_id);
    $tags = wp_get_post_tags($post->id);
    $old_tag_id = $new_tag_id = false;
    if (isset($tags[0]->term_id)) $old_tag_id = $tags[0]->term_id;
    if (isset($post_array['tax_input']['post_tag'][0])) $new_tag_id = $post_array['tax_input']['post_tag'][0];
    if ($old_tag_id === $new_tag_id) return $post_data;
    if (($old_tag_id === false) && $new_tag_id) { $this->add_post_to_edition($post, $new_tag_id); return $post_data; }
    if ($old_tag_id && ($new_tag_id === false)) { $this->remove_post_from_edition($post, $old_tag_id); return $post_data; }

    if ($old_tag_id !== $new_tag_id) {
      $this->remove_post_from_edition($post, $old_tag_id);
      $this->add_post_to_edition($post, $new_tag_id);
      return $post_data;
    }

    return $post_data;
  }

  function remove_post_from_edition($post, $tag_id) {
    $post_id = $post->id;
    $edition_number = $tag_id;
    $args = array(
      'post_type' => 'pugpig_edition',
      'meta_query' => array(
        'meta_key' => 'edition_number',
        'meta_value' => $edition_number
      )
    );
    $edition = Timber::get_post($args);
    $linked_post_ids = $edition->flatplan; //Array
    if (is_array($linked_post_ids)) {
      if(($key = array_search($post_id, $linked_post_ids)) !== false) {
        unset($linked_post_ids[$key]);
      }
      update_field('flatplan', $linked_post_ids, $edition->id);
    }
  }

  function add_post_to_edition(TimberPost $post, $tag_id) {
    $post_id = $post->id;
    $edition_number = $tag_id;
    $args = array(
      'post_type' => 'pugpig_edition',
      'meta_query' => array(
        'meta_key' => 'edition_number',
        'meta_value' => $edition_number
      )
    );
    $edition = Timber::get_post($args);
    $linked_post_ids = is_array($edition->flatplan) ? $edition->flatplan : array(); //Array
    if (!in_array($post_id, $linked_post_ids)) {
      array_push($linked_post_ids, $post_id);
      update_field('flatplan', $linked_post_ids, $edition->id);
    }
  }

  function edition_hooks(TimberPost $edition) {
    if ($edition->post_type !== 'pugpig_edition') return;
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

      echo view('@AgreablePugpigPlugin/status-column.twig', array(
        'post_status' => $post_status,
        'custom' => $page_count ? $page_count : 0,
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

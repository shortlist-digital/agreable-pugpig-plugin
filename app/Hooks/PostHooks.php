<?php namespace AgreablePugpigPlugin\Hooks;

use add_action;
use add_filter;

use Jigsaw;
use Timber;
use TimberPost;

class Posthooks {
  public function init() {
    add_action('save_post', array($this, 'run_hooks'));
    add_filter('wp_insert_post_data', array($this, 'check_for_tag_update'), 12, 3);

  }

  function run_hooks($post_id) {
    $post = new TimberPost($post_id);
    if ($this->check($post)) return;
  }

  function check(TimberPost $post) {
    if (wp_is_post_revision($post->ID)) {return true;}
    if ($post->post_type !== 'post') {return true;}
    return false;
  }

  function check_for_tag_update($post_data, $post_array) {
    if (!empty($post_array['ID'])) {
      $post_id = $post_array['ID'];
      $post = new TimberPost($post_id);
      if ($this->check($post)) return;
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
    }
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

}


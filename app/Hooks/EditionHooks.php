<?php namespace AgreablePugpigPlugin\Hooks;

use add_filter;

class EditionHooks {

  public function init() {
    add_action('new_to_auto-draft', array($this, 'new_edition'));
  }

  function check($post) {
    return ($post->post_type !== 'pugpig_edition');
  }

  function new_edition($post) {
    if ($this->check($post)) return;
    return update_post_meta($post->ID, 'edition_key', $post->ID);
  }

}


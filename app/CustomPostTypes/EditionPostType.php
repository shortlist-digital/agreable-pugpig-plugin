<?php namespace AgreablePugpigPlugin\CustomPostTypes;

class EditionPostType {

  public function register() {
    \add_action('init', array($this, 'editions_post_type'), 50);
  }

  function editions_post_type() {
    $labels = array(
      'name' => _x('Editions', 'post type general name'),
      'singular_name' => _x('Edition', 'post type singular name'),
      'add_new' => _x('Add New', 'Edition item'),
      'add_new_item' => __('Add New Pugpig Edition'),
      'edit_item' => __('Edit Edition Item'),
      'new_item' => __('New Edition Item'),
      'view_item' => __('View Edition Item'),
      'search_items' => __('Search Editions'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'set_featured_image'    => __( 'Set Edition Cover', 'text_domain' ),
      'remove_featured_image' => __( 'Remove Edition Cover', 'text_domain' ),
      'use_featured_image'    => __( 'Use as Edition Cover', 'text_domain' ),
      'parent_item_colon' => ''
    );

    $args = array(
      'labels' => $labels,
      'singular_label' => $labels['singular_name'],
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'menu_icon' => 'dashicons-book-alt',
      'supports' => array('title', 'excerpt','thumbnail') // Custom Fields for debug 'custom-fields'
      );

    \register_post_type( 'pugpig_edition' , $args );
  }
}

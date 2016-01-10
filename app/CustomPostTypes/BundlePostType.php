<?php namespace AgreablePugpigPlugin\CustomPostTypes;

use add_action;
use register_post_type;

class BundlePostType {

  public function register() {
    add_action('init', array($this, 'ad_bundle_post_type'), 50);
  }

  // Register Custom HTML Zips
  function ad_bundle_post_type() {
    $labels = array(
      'name'                  => _x( 'HTML Zips', 'HTML Zips General Name', 'text_domain' ),
      'singular_name'         => _x( 'HTML Zip', 'HTML Zips Singular Name', 'text_domain' ),
      'menu_name'             => __( 'HTML Zips', 'text_domain' ),
      'name_admin_bar'        => __( 'HTML Zips', 'text_domain' ),
      'archives'              => __( 'HTML Zip Archives', 'text_domain' ),
      'parent_HTML Zip_colon'     => __( 'Parent HTML Zip:', 'text_domain' ),
      'all_HTML Zips'             => __( 'All HTML Zips', 'text_domain' ),
      'add_new_HTML Zip'          => __( 'Add New HTML Zip', 'text_domain' ),
      'add_new'               => __( 'Add New', 'text_domain' ),
      'new_HTML Zip'              => __( 'New HTML Zip', 'text_domain' ),
      'edit_HTML Zip'             => __( 'Edit HTML Zip', 'text_domain' ),
      'update_HTML Zip'           => __( 'Update HTML Zip', 'text_domain' ),
      'view_HTML Zip'             => __( 'View HTML Zip', 'text_domain' ),
      'search_HTML Zips'          => __( 'Search HTML Zip', 'text_domain' ),
      'not_found'             => __( 'Not found', 'text_domain' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
      'featured_image'        => __( 'Featured Image', 'text_domain' ),
      'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
      'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
      'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
      'insert_into_HTML Zip'      => __( 'Insert into HTML Zip', 'text_domain' ),
      'uploaded_to_this_HTML Zip' => __( 'Uploaded to this HTML Zip', 'text_domain' ),
      'HTML Zips_list'            => __( 'HTML Zips list', 'text_domain' ),
      'HTML Zips_list_navigation' => __( 'HTML Zips list navigation', 'text_domain' ),
      'filter_HTML Zips_list'     => __( 'Filter HTML Zips list', 'text_domain' ),
    );
    $args = array(
      'label'                 => __( 'HTML Zip', 'text_domain' ),
      'description'           => __( 'HTML Zips Description', 'text_domain' ),
      'labels'                => $labels,
      'supports'              => array( ),
      'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'menu_icon'             => 'dashicons-welcome-add-page'
    );
    register_post_type( 'pugpig_ad_bundle', $args );
  }
}

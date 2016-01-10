<?php

if ( ! class_exists( 'ACF' ) ) {
  add_action( 'admin_notices', function() {
    echo '<div class="error"><p>ACF5 Pro is not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
  } );
  return;
}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
  'key' => 'edition',
  'title' => 'Edition',
  'fields' => array (
    array (
      'key' => 'edition_flatplan',
      'label' => 'Flatplan',
      'name' => 'flatplan',
      'type' => 'relationship',
      'instructions' => 'Add, remove and re-order posts in this edition here',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'post_type' => array (
        0 => 'post',
        1 => 'pugpig_ad_bundle'
      ),
      'taxonomy' => array (
      ),
      'filters' => array (
        0 => 'search',
       //1 => 'taxonomy',
      ),
      'elements' => '',
      'min' => '',
      'max' => '',
      'return_format' => 'id',
    ),
    array (
      'key' => 'edition_number',
      'label' => 'Edition Number',
      'name' => 'edition_number',
      'type' => 'taxonomy',
      'instructions' => 'Set the Wordpress tag for this edition',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'taxonomy' => 'post_tag',
      'field_type' => 'multi_select',
      'allow_null' => 0,
      'add_term' => 1,
      'load_save_terms' => 0,
      'return_format' => 'id',
      'multiple' => 0,
    ),
    array (
      'key' => 'edition_deleted',
      'label' => 'Edition Deleted',
      'name' => 'edition_deleted',
      'type' => 'true_false',
      'instructions' => 'In order to delete an edition that has been published, use this field to set a \'tombstone\'',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'message' => '',
      'default_value' => 0,
    ),
    array (
      'key' => 'edition_sharing_link',
      'label' => 'Edition Sharing Link',
      'name' => 'edition_sharing_link',
      'type' => 'url',
      'instructions' => 'This is an optional sharing link for all pages',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
    ),
  ),
  'location' => array (
    array (
      array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'pugpig_edition',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => array('excerpt'),
));

endif;

<?php namespace AgreablePugpigPlugin;

use AgreablePugpigPlugin\Helper;
// $ns = Helper::get('agreable_namespace');
$ns = 'agreable_pugpig';

/*
 * Although we're in the Herbert panel file, we're not using any built in
 * panel functionality because you have to write you're own HTML forms and
 * logic. We're using ACF instead but seems sensible to leave ACF logic in
 * here (??).
 */


// Constructed using (lowercased and hyphenated) 'menu_title' from above.
$options_page_name = 'acf-options';

if( function_exists('register_field_group') ):

register_field_group(array (
  'key' => 'group_'.$ns.'_plugin',
  'title' => 'Pugpig API Credentials',
  'fields' => array (
    array (
      'key' => 'pugpig_itunes_secret',
      'label' => 'Pugpig iTunes Secret',
      'name' => 'pugpig_itunes_secret',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'maxlength' => '',
      'readonly' => 0,
      'disabled' => 0,
    ),
    array (
      'key' => 'pugpig_subscription_prefix',
      'label' => 'Pugpig Subscription Prefix',
      'name' => 'pugpig_subscription_prefix',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => 'com.mycompany.subscription',
      'prepend' => '',
      'append' => '',
      'maxlength' => '',
      'readonly' => 0,
      'disabled' => 0,
    ),
    array (
      'key' => 'pugpig_secret',
      'label' => 'Pugpig Secret',
      'name' => 'pugpig_secret',
      'type' => 'password',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'readonly' => 0,
      'disabled' => 0,
    )
  ),
  'location' => array (
    array (
      array (
        'param' => 'options_page',
        'operator' => '==',
        'value' => $options_page_name,
      ),
    ),
  ),
  'menu_order' => 10,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
));

endif;


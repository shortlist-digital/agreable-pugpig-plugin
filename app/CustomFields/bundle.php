<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
  'key' => 'ad_bundl',
  'title' => 'Bundle',
  'fields' => array (
    array (
      'key' => 'ad_bundle_zip_file',
      'label' => 'Zip File',
      'name' => 'ad_bundle_zip_file',
      'type' => 'file',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'return_format' => 'array',
      'library' => 'uploadedTo',
      'min_size' => '',
      'max_size' => '',
      'mime_types' => 'zip',
    ),
    array (
      'key' => 'ad_bundle_html_file',
      'label' => 'HTML File',
      'name' => 'ad_bundle_html_file',
      'type' => 'text',
      'instructions' => 'The HTML file from within the ZIP that will be used in the edition. It is automatically detected by scanning through the ZIP file contents for the first HTML file, but you may override it.',
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
  ),
  'location' => array (
    array (
      array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'pugpig_ad_bundle',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => array (
    0 => 'permalink',
    1 => 'the_content',
    2 => 'excerpt',
    3 => 'custom_fields',
    4 => 'discussion',
    5 => 'comments',
    6 => 'revisions',
    7 => 'slug',
    8 => 'author',
    9 => 'format',
    10 => 'page_attributes',
    11 => 'featured_image',
    12 => 'send-trackbacks',
  ),
));

endif;

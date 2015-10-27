<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Agreable Pugpig Plugin
 * Plugin URI:        https://github.com/shortlist-digital/agreable-plugin-plugin
 * Description:       Packages posts into PugPig compatible issues
 * Version:           0.1.0
 * Author:            Shortlist Media
 * Author URI:        http://shortlistmedia.co.uk
 * License:           MIT
 */

if ( ! class_exists( 'Jigsaw' ) ) {
  add_action( 'admin_notices', function() {
      echo '<div class="error"><p>Jigsaw not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url
( 'plugins.php' ) ) . '</a></p></div>';
    } );
  return;
}

if(file_exists(__DIR__ . '/vendor/autoload.php')){
  require_once __DIR__ . '/vendor/autoload.php';
} else if(file_exists(__DIR__ . '/../../../../vendor/getherbert/')){
  require_once __DIR__ . '/../../../../vendor/autoload.php';
} else {
  throw new Exception('Something went badly wrong');
}

if(file_exists(__DIR__ . '/vendor/getherbert/framework/bootstrap/autoload.php')){
  require_once __DIR__ . '/vendor/getherbert/framework/bootstrap/autoload.php';
} else if(file_exists(__DIR__ . '/../../../../vendor/getherbert/framework/bootstrap/autoload.php')){
  require_once __DIR__ . '/../../../../vendor/getherbert/framework/bootstrap/autoload.php';
} else {
  throw new Exception('Something went badly wrong');
}

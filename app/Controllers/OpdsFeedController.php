<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class OpdsFeedController {

  public function index() {
    define('PUGPIG_CURRENT_VERSION', '2.3.8');
    require_once(WP_PLUGIN_DIR . '/pugpig/feeds/pugpig_feed_opds.php');
  }

}

<?php namespace AgreablePugpigPlugin\Controllers;

use Timber;
use TimberSite;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class OpdsFeedController {

  public function index() {
    $this->set_headers();
    $editions_data = $this->get_recent_editions();
    return view('@AgreablePugpigPlugin/issues_feed.twig', array(
      'editions' => $editions_data,
      'site' => new TimberSite
    ))->getBody();
  }

  public function set_headers() {
    header('Content-Type:application/atom+xml; charset=utf-8');
  }

  public function get_recent_editions() {
    $args = array(
      'post_type' => 'pugpig_edition',
      'order' => 'DESC',
      'post_status' => 'publish',
      'orderby' => 'date'
    );
    $editions = Timber::get_posts($args);
    return $editions;
  }

}

<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;

class FeedGeneratorController {

  function convert_timestamp ($timestamp=0) {
    if (!$timestamp) {
      $timestamp = time();
    }
    $new_time = date('Y-m-d\TH:i:s', $timestamp);

    $matches = array();
    if (preg_match('/^([\-+])(\d{2})(\d{2})$/', date('O', $timestamp), $matches)) {
      $new_time .= $matches[1] . $matches[2] . ':' . $matches[3];
    } else {
      $new_time .= 'Z';
    }
    return $new_time;
  }

  function edition_feed($feed_data) {
    return view('@AgreablePugpigPlugin/edition_feed.twig', array(
      'feed' => $feed_data
    ))->getBody();
  }

}



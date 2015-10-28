<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;
use AgreablePugpigPlugin\Controllers\FeedGeneratorController;

class EditionsFeedController {
  public function feed($id, Http $http) {
  $headers = [];
  $headers['Content-Type'] = "application/atom+xml";
  return response(FeedGeneratorController::edition_feed($id), 200, $headers);
  }
}

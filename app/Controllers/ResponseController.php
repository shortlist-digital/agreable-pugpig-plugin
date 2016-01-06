<?php namespace AgreablePugpigPlugin\Controllers;

use Herbert\Framework\Http;

class ResponseController {

  public function success($content, $last_modified) {
    $headers = $this->build_success_headers($last_modified);
    return response($content, 200, $headers);
  }

  public function manifest($content, $last_modified) {
    $headers = $this->build_success_headers($last_modified);
    $headers['Content-Type'] = "text/cache-manifest;";
    return response($content, 200, $headers);
  }

  public function package($content, $last_modified) {
    $headers = $this->build_success_headers($last_modified);
    $headers['Content-Disposition'] = "inline";
    $headers['Content-Type'] = "application/pugpigpkg+xml";
    $headers['X-Pugpig-Status'] = "published";
    return response($content, 200, $headers);
  }

  public function atom($content, $last_modified) {
    $headers = $this->build_success_headers($last_modified);
    $headers['Content-Type'] = "application/atom+xml";
    return response($content, 200, $headers);
  }

  private function build_success_headers($modified) {
    $cache_time = 3600;
    $modified = gmdate('D, d M Y H:i:s', strtotime($modified)).' GMT';
    $etag = '"' . md5($modified) . '"';
    $headers['Cache-Control'] = "max-age: $cache_time";
    $headers['Expires'] = gmdate('D, d M Y H:i:s', time()+$cache_time).' GMT';
    $headers['Last-Modified'] = $modified;
    $headers['Etag'] = $etag;
    $time_go = new \TimeAgo();
    $post_time = gmdate("Y\/n\/j\ H:i:s", strtotime($modified));
    $last_update = $time_go->inWords($post_time);
    $headers['X-pugpig-LM-Ago'] = $last_update;
    return $headers;
  }



}

<?php namespace AgreablePugpigPlugin;

/** @var \Herbert\Framework\Router $router */

$router->get([
  'as'   => 'editionFeed',
  'uri'  => '/editionfeed/{id}/pugpig_atom_contents.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\EditionsFeedController@feed'
]);

$router->get([
  'as'   => 'pugpigIndex',
  'uri'  => '/{year}/{month}/{day}/{slug}/pugpig.manifest',
  'uses' => function($year, $month, $day, $slug) {
    echo "generate matifest here";
  }
]);


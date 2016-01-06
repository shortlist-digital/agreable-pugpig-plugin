<?php namespace AgreablePugpigPlugin;

/** @var \Herbert\Framework\Router $router */

$router->get([
  'as'   => 'editionFeed',
  'uri'  => '/editionfeed/{id}/pugpig_atom_contents.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\EditionsFeedController@feed'
]);

$router->get([
  'as'   => 'editionFeed',
  'uri'  => '/editionfeed/{id}/package.xml',
  'uses' => __NAMESPACE__ . '\Controllers\EditionsFeedController@package_list'
]);

$router->get([
  'as'   => 'pugpigIndex',
  'uri'  => '/{year}/{month}/{day}/{slug}/pugpig.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\PostManifestController@index'
]);

$router->get([
  'as'   => 'pugpigIndex',
  'uri'  => '/{year}/{month}/{day}/{slug}/pugpig_index.html',
  'uses' => __NAMESPACE__ . '\Controllers\RelativeFilesController@index'
]);

$router->get([
  'as'   => 'opdsFeed',
  'uri'  => '/editions.xml',
  'uses' => __NAMESPACE__ . '\Controllers\OpdsFeedController@index'
]);

$router->get([
  'as'   => 'opdsFeed',
  'uri'  => '/feed/opds',
  'uses' => __NAMESPACE__ . '\Controllers\OpdsFeedController@index'
]);

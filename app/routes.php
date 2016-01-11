<?php namespace AgreablePugpigPlugin;

/** @var \Herbert\Framework\Router $router */

// Edition endpoints
$router->get([
  'as'   => 'editionFeed',
  'uri'  => '/editionfeed/{id}/pugpig_atom_contents.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\EditionsFeedController@feed'
  ]);

$router->get([
  'as'   => 'editionPackage',
  'uri'  => '/editionfeed/{id}/package.xml',
  'uses' => __NAMESPACE__ . '\Controllers\EditionsFeedController@package_list'
]);


// Manifest endpoints
$router->get([
  'as'   => 'pugpigIndex',
  'uri'  => '/{year}/{month}/{day}/{slug}/pugpig.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\PostManifestController@index'
]);

$router->get([
  'as'   => 'pugpigBundleIndex',
  'uri'  => '/pugpig_ad_bundle/{slug}/pugpig.manifest',
  'uses' => __NAMESPACE__ . '\Controllers\BundleManifestController@index'
]);

// HTML Endpoints
$router->get([
  'as'   => 'pugpigIndexFile',
  'uri'  => '/pugpig_ad_bundle/{slug}/pugpig.manifest',
  'uri'  => '/{year}/{month}/{day}/{slug}/pugpig_index.html',
  'uses' => __NAMESPACE__ . '\Controllers\RelativeFilesController@index'
]);

$router->get([
  'as'   => 'pugpigBundleIndexFile',
  'uri'  => '/pugpig_ad_bundle/{slug}/pugpig_index.html',
  'uses' => __NAMESPACE__ . '\Controllers\RelativeFilesController@bundle'
]);


// Feed Endpoints
$router->get([
  'as'   => 'opdsFeedPackage',
  'uri'  => '/editions.xml',
  'uses' => __NAMESPACE__ . '\Controllers\OpdsFeedController@index'
]);

$router->get([
  'as'   => 'opdsFeed',
  'uri'  => '/feed/opds',
  'uses' => __NAMESPACE__ . '\Controllers\OpdsFeedController@index'
  ]);

// Subscription Endpoints
$router->get([
  'as' => 'appleSubscriptionGet',
  'uri' => '/itunes/edition_credentials',
  'uses' => __NAMESPACE__ . '\Controllers\AppleSubscriptionController@get'
  ]);

$router->post([
  'as' => 'appleSubscriptionGet',
  'uri' => '/itunes/edition_credentials',
  'uses' => __NAMESPACE__ . '\Controllers\AppleSubscriptionController@post'
]);


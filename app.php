<?php

// Bootstrap
// ---------

require_once 'bootstrap.php';
session_start();


// Silex application
// -----------------

$app = new \Silex\Application();


// Routes
// ------

$app->get('/{provider}', function($provider) use($app) {
  // Initialize MultiPass
  $config    = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/'.$provider.'.yml');
  $multipass = new \MultiPass\MultiPass($provider, $config);
  
  // Do the request phase routine using MultiPass
  $multipass->request_phase();
})
->assert('provider', '^(facebook|foursquare|github|instagram|twitter)$');

$app->get('/{provider}/callback', function($provider) use($app) {
  // Initialize MultiPass
  $config    = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/'.$provider.'.yml');
  $multipass = new \MultiPass\MultiPass($provider, $config);
  
  // Do the callback phase routine using MultiPass
  $auth_hash = $multipass->callback_phase();

  echo "<pre>";
  print_r($auth_hash->toArray());
  echo "</pre>";
})
->assert('provider', '^(facebook|foursquare|github|instagram|twitter)$');

$app->run();

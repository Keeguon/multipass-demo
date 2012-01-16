<?php

// Bootstrap
// ---------

require_once 'bootstrap.php';
session_start();


// MultiPass configuration
// -----------------------

$multipass = \MultiPass\Configuration::getInstance();
foreach (array('facebook', 'foursquare', 'github', 'instagram', 'twitter') as $provider) {
  $config = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/'.$provider.'.yml');
  $multipass->registerConfig($provider, $config);
}
$multipass->register();


// Silex application
// -----------------

$app = new \Silex\Application();


// Routes
// ------

$app->get('/', function() use ($app) {
  echo "<pre>";
  print_r($_REQUEST);
  echo "</pre>";
});

$app->get('/auth/{provider}', function($provider) use ($multipass) {
  $strategy = $multipass->getStrategy($provider);
  $strategy->requestPhase();
})
->assert('provider', '^(facebook|foursquare|github|instagram|twitter)$');

$app->get('/auth/{provider}/callback', function($provider) use ($multipass) {
  $strategy = $multipass->getStrategy($provider);
  $authHash = $strategy->callbackPhase();

  echo "<pre>";
  print_r($authHash->toArray());
  echo "</pre>";
})
->assert('provider', '^(facebook|foursquare|github|instagram|twitter)$');

$app->run();

<?php

// Loader
// ------

$loader = require_once __DIR__ . "/vendor/autoload.php";


// Bootstrap
// ---------

session_start();


// MultiPass configuration
// -----------------------

$multipass = \MultiPass\Configuration::getInstance();
foreach (array('facebook') as $provider) {
  $config = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/'.$provider.'.yml');
  $multipass->registerConfig($provider, $config);
}
$multipass->register();


// Silex application
// -----------------

$app = new \Silex\Application();
$app['debug'] = true;


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
->assert('provider', '^(facebook|foursquare|github|google|instagram|twitter)$');

$app->get('/auth/{provider}/callback', function($provider) use ($multipass) {
  $strategy = $multipass->getStrategy($provider);
  $authHash = $strategy->callbackPhase();

  echo "<pre>";
  print_r($authHash->toArray());
  echo "</pre>";
})
->assert('provider', '^(facebook|foursquare|github|google|instagram|twitter)$');

$app->run();

<?php

require_once 'bootstrap.php';


// MultiPass configuration
$config    = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/facebook.yml');
$multipass = new \MultiPass\MultiPass('facebook', $config);
error_log(print_r($multipass->provider, true));

// Silex application
$app = new \Silex\Application();

$app->get('/facebook', function() use($app, $multipass) {
  $multipass->request_phase();
});

$app->get('/facebook/callback', function() use($app, $multipass) {
  $auth_hash = $multipass->callback_phase();

  echo "<pre>";
  print_r($auth_hash->toArray());
  echo "</pre>";
});

$app->run();

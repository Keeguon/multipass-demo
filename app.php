<?php

require_once 'bootstrap.php';


// MultiPass configuration
$config    = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/config/facebook.yml');
$multipass = new \MultiPass\MultiPass('facebook', $config);

// Silex application
$app = new \Silex\Application();

$app->get('/facebook', function() use($app, $multipass) {
  $multipass->request_phase();
});

$app->get('/facebook/callback', function() use($app, $multipass) {
  $multipass->callback_phase();
  $response = $multipass->provider->token->get($multipass->provider->client->site.'/me', array('parse' => 'json'));

  echo "<pre>";
  print_r($response->parse());
  echo "</pre>";
});

$app->run();

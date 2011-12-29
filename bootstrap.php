<?php

if (false === class_exists('Symfony\Component\ClassLoader\UniversalClassLoader', false)) {
  require_once __DIR__.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';
}

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'   => array(__DIR__.'/vendor', __DIR__.'/vendor/Silex/vendor')
  , 'Silex'     => array(__DIR__.'/vendor/Silex/src')
  , 'OAuth2'    => __DIR__.'/vendor/oauth2-php/src'
  , 'MultiPass' => __DIR__.'/vendor/MultiPass/src'
));
$loader->registerPrefixes(array(
    'Pimple' => __DIR__.'/vendor/Silex/vendor/pimple/lib'
));
$loader->register();

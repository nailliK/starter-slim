<?php

/** This is our bootstrap file. **/

session_start();

require __DIR__.'/../vendor/autoload.php';

// instantiate Slim
$container = new \Slim\Container([
	'settings' => [
		'displayErrorDetails' => true
	]
]);
$app = new \Slim\App($container);

// instantiate Sintenel
$container['sentinel'] = (new \Cartalyst\Sentinel\Native\Facades\Sentinel())->getSentinel();

// instantiate Twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../views/');
$loader->addPath(__DIR__, 'public');
$container->twig = new Twig_Environment($loader, array(
    // 'cache' => __DIR__ . '/../cache',
    'cache' => false,
));


// directory iterator
function rsearch($folder, $pattern) {
    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList = array_merge($fileList, $file);
    }
    return $fileList;
}

// load configs
$files = rsearch(__DIR__ . '/../config/', '/^.+\.php$/i');
foreach ( $files as $filename ) { require $filename; }

// logged in user variable
$loggedUser = $container->sentinel->check();

// load routes
$files = rsearch(__DIR__ . '/../routes/', '/^.+\.php$/i');
foreach ( $files as $filename ) { require $filename; }

// run twig
$app->run();
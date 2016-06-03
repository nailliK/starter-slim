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

// instantiate Sentinel
$container['sentinel'] = (new \Cartalyst\Sentinel\Native\Facades\Sentinel())->getSentinel();

// instantiate Twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../views/');
$loader->addPath(__DIR__, 'public');
$container['twig'] = new Twig_Environment($loader, array(
    'cache' => __DIR__ . '/../cache',
    // 'cache' => false,
));

function load_files($dir, $files) {
	foreach ($files as $file) {
		require $dir . $file . '.php';
	}
}

// load configs

$files = [
	'globals',
	'email',
	'database',
	'migrations'
];
foreach ($files as $file) {
	require __DIR__ . '/../config/' . $file . '.php';
}

// load resources
$files = [
	'markdown',
	'svg',
	'wordpress'
];
foreach ($files as $file) {
	require __DIR__ . '/../resources/' . $file . '.php';
}

// logged in user variable
$loggedUser = $container->sentinel->check();

// load routes
$files = [
	'api/auth',
	'api/user',
	'dashboard/index',
	'static/index'
];
foreach ($files as $file) {
	require __DIR__ . '/../routes/' . $file . '.php';
}

// run twig
$app->run();
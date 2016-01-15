<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// dashboard homepage
$app->get('/dashboard', function (Request $request, Response $response) use($loggedUser) {
	return $this->twig->render('dashboard/index.twig', array('user' => $loggedUser, 'title' => 'Dashboard'));

	if (!$loggedUser) {
		$new_response = $response->withHeader('refresh', '0.5; ' . '/login');
		return $new_response;
	} else {
		return $this->twig->render('dashboard/index.twig', array('user' => $loggedUser, 'title' => 'Dashboard'));
	}

})->setName('dashboard_home');
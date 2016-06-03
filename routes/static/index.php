<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// homepage
$app->get('/', function (Request $request, Response $response) use($loggedUser) {
	return $this->twig->render('home/index.twig', array('the' => 'variables', 'go' => 'here'));
})->setName('home');

// login
$app->get('/login', function (Request $request, Response $response) {
	return $this->twig->render('home/login.twig', array('the' => 'variables', 'go' => 'here'));
})->setName('login');

// logout
$app->get('/logout', function(Request $request, Response $response) {
	$this->sentinel->logout(null,true);
	
	$new_response = $response->withHeader('refresh', '0.5; ' . '/login');
	return $new_response;
});

// signup
$app->get('/signup', function (Request $request, Response $response) {
	return $this->twig->render('home/signup.twig', array('the' => 'variables', 'go' => 'here'));
})->setName('signup');

// account activation
$app->get("/activate/{code:[a-zA-Z0-9]+}/{id:[0-9]+}", function (Request $request, Response $response, $args) {
	// create response message
	$message = '';
	$redirect = '';
	
	// get activation data
	$code = $args['code'];
    $id = $args['id'];
	
	// activation
	$activationRepository = new Cartalyst\Sentinel\Activations\IlluminateActivationRepository;
    $activation = Cartalyst\Sentinel\Activations\EloquentActivation::where("code", $code)->first();
	
    $user = $this->sentinel->findById($activation->user_id);
	
	// if user doesn't exist
	if (!($user = $this->sentinel->findById($id))) {
        $message = 'User not found';
		$redirect = '/';
	} else {
	    if (!$activationRepository->complete($user, $code)) {
			if ($activationRepository->completed($user)) {
				$message = 'Activation Successful. Redirecting to login.';
				$redirect = '/login';
			}
		}
	}
	
	// set redirection
	$new_response = $response->withHeader('refresh', '.5; ' . $redirect);
	return $new_response;
})->setName('activate');